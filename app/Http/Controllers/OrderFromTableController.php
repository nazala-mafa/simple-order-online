<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\OrderFromTable;
use App\Models\OrderFromTableDetail;
use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OrderFromTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->check() || auth()->user()->role !== 'seller') {
            return view('guest.homepage.orders', [
                'noHeader' => true,
                'title' => 'List Orders',
                'orders' => OrderFromTable::whereNotIn('status', ['done'])->get(),
                'tableId' => request()->table_id
            ]);
        }
        $orders = OrderFromTableDetail
            ::whereRelation('product', 'user_id', auth()->user()->id)
            ->whereRelation('order', 'status', '!=', 'ready')
            ->get();
        return view('seller.order.index', [
            'title' => 'Order list',
            'orders' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => ['required', 'numeric'],
            'on_behalf_of' => ['required'],
            'products_amount.*'  => ['required', 'numeric']
        ]);
        $productsKey = array_keys($request->products_amount);
        $products = Product::whereIn('id', $productsKey)->get();
        $sumTotal = 0;
        $orderDetail = $products->map(function($row)use($request, &$sumTotal) {
            $sumTotal += $request->products_amount[$row->id] * $row->price;
            return [
                'product_id' => $row->id,
                'price' => $row->price,
                'amount' => $request->products_amount[$row->id],
                'total' => $request->products_amount[$row->id] * $row->price,
                'status' => 'pending'
            ];
        });

        $orderId = OrderFromTable::insertGetId([
            'table_id' => $request->table_id,
            'on_behalf_of' => $request->on_behalf_of,
            'sum_total' => $sumTotal,
            'status' => 'in_payment', 
            'payment_url' => '',
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);

        $xenditApiKey = Option::where('key', 'xendit_api_key')->pluck('value')->first() ?? false;
        if($xenditApiKey) {
            $guzzleClient = new Client();
            $response = $guzzleClient->post('https://api.xendit.co/v2/invoices', [
                'headers' => [ 'Content-Type' => 'application/json' ],
                'auth' => [$xenditApiKey, ''],
                'json' => [
                    "external_id" => "order-" . $orderId,
                    "amount" => $sumTotal,
                    "description" => (env('APP_NAME') ?? 'Food') . ' Payment',
                    "invoice_duration" => 60*60, //detik
                ]
            ]);
            $invoiceUrl = json_decode($response->getBody()->getContents())->invoice_url;
            OrderFromTable::find($orderId)->update([
                'payment_url' => $invoiceUrl
            ]);
        }


        $orderDetail = $orderDetail->map(function($row) use($orderId) { 
            $row['order_id'] = $orderId;
            return $row; 
        });
        OrderFromTableDetail::insert($orderDetail->toArray());

        return redirect()->to($invoiceUrl);
        // return redirect()->route('table-order.index', ['table_id' => $request->table_id])->with('message', 'New order has been added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = OrderFromTableDetail::find($id);

        if($order->order->status === 'pending') {
            $order->order->status = 'proccess';
            $order->order->save();
        } //update order

        $order->status = $request->status;
        $order->save(); //update order detail

        if($order->order->detail->filter(function($row) {
            return $row->status == 'ready';
        })->count() === $order->order->detail->count()) {
            $order->order->status = 'ready';
            $order->order->save();
            return redirect()->back()->with('message', 'Order Ready');
        } // update order to ready if all order detail is ready

        return redirect()->back()->with('message', 'Order Detail Updated');
    }

    public function xendit_callback(Request $request) 
    {
        $request->validate([
            'external_id' => ['required'],
            'amount' => ['required']
        ]);

        OrderFromTable::where([
            'id' => str_replace('order-', '', $request->external_id),
            'sum_total' => $request->amount
        ])->first()->update([
            'status' => 'pending',
            'payment_url' => ''
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
