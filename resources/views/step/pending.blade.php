<div class="track mt-4">
    <div class="step active">
        <span class="icon"></span>
        <span class="text">Pending</span>
        <small class="text">{{$item_product['order_item_status']=='pending' ? $item_product['order_item_updated_at'] :''}} </small>
    </div>
    
    <div class="step">
        <span class="icon"></span>
        <span class="text">Ordered</span>
        <small class="text">{{$item_product['order_item_status']=='ordered' ? $item_product['order_item_updated_at'] :''}} </small>
    </div>
    
    <div class="step">
        <span class="icon"></span>
        <span class="text">Processing</span>
        <small class="text">{{$item_product['order_item_status']=='processing' ? $item_product['order_item_updated_at'] :''}} </small>
    </div>
    
    <div class="step">
        <span class="icon"></span>
        <span class="text">Shipped</span>
        <small class="text">{{$item_product['order_item_status']=='shipped' ? $item_product['order_item_updated_at'] :''}} </small>
    </div>
    
    <div class="step">
        <span class="icon"></span>
        <span class="text">Delivered</span>
        <small class="text">{{$item_product['order_item_status']=='delivered' ? $item_product['order_item_updated_at'] :''}} </small>
    </div>
    
    
    
</div>