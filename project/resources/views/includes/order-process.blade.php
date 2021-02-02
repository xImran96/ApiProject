@if($order->status == 'pending')

                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title">Order placed</div>
                                            </li>
                                            @if($order->method=='post_paid')
                                            <li class="">
                                                <div class="icon">2</div> 
                                                <div class="title">seller approved</div>
                                            </li>
                                            <li class="">
                                                <div class="icon">3</div>
                                                <div class="title">buyer accept</div>
                                            </li>
                                            @endif
                                            <li class="">
                                                <div class="icon">@if($order->method=='post_paid')4 @else 2 @endif</div>
                                                <div class="title">On review</div>
                                            </li>
                                            <li class="">
                                                <div class="icon"> @if($order->method=='post_paid')5 @else 3 @endif </div>
                                                <div class="title">On delivery</div>
                                            </li>
                                            <li class="">
                                                <div class="icon">@if($order->method=='post_paid')6 @else 4 @endif </div>
                                                <div class="title">Delivered</div>
                                            </li>
                                    </ul>

@elseif($order->post_paid_confirm==2)

                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title">Order placed</div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">2</div>
                                                @if($order->method=='post_paid')
                                                <div class="title">seller approved</div>
                
                                                @endif 
                                            </li>
                                            @if($order->method=='post_paid')
                                            <li class="active">
                                                <div class="icon">3</div>
                                                <div class="title">buyer accept</div>
                                            </li>
                                            @endif
                                            <li class="">
                                                <div class="icon">@if($order->method=='post_paid')4 @else 2 @endif</div>
                                                <div class="title">On review</div>
                                            </li>
                                            <li class="">
                                                <div class="icon">5</div>
                                                <div class="title">On delivery</div>
                                            </li>
                                            <li class="">
                                                <div class="icon">6</div>
                                                <div class="title">Delivered</div>
                                            </li>
                                    </ul>
                                    @elseif($order->post_paid_confirm==1)
                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title">Order placed</div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">2</div>
                                                @if($order->method=='post_paid')
                                                <div class="title">seller approved</div>
                                            
                                                @endif 
                                            </li>
                                            @if($order->method=='post_paid')
                                            <li class="">
                                                <div class="icon">3</div>
                                                <div class="title">buyer accept</div>
                                            </li>
                                            @endif
                                            <li class="">
                                                <div class="icon">@if($order->method=='post_paid')4 @else 2 @endif</div>
                                                <div class="title">On review</div>
                                            </li>
                                            <li class="">
                                                <div class="icon">5</div>
                                                <div class="title">On delivery</div>
                                            </li>
                                            <li class="">
                                                <div class="icon">6</div>
                                                <div class="title">Delivered</div>
                                            </li>
                                    </ul>

@elseif($order->status == 'on delivery')

                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title">Order placed</div>
                                            </li>
                                           
                                            @if($order->method=='post_paid')
                                            <li class="active">
                                                <div class="icon">2</div> 
                                                <div class="title">seller approved</div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">3</div>
                                                <div class="title">buyer accept</div>
                                            </li>
                                            @endif
                                            <li class="active">
                                                <div class="icon">@if($order->method=='post_paid')4 @else 2 @endif</div>
                                                <div class="title">On review</div>
                                            </li>
                                            <li class="active">
                                                <div class="icon"> @if($order->method=='post_paid')5 @else 3 @endif</div>
                                                <div class="title">On delivery</div>
                                            </li>
                                            <li class="">
                                                <div class="icon"> @if($order->method=='post_paid')6 @else 4 @endif</div>
                                                <div class="title">Delivered</div>
                                            </li>
                                    </ul>

                                    
@elseif($order->status == 'processing')
<ul class="process-steps">
        <li class="active">
            <div class="icon">1</div>
            <div class="title">Order placed</div>
        </li>
        @if($order->method=='post_paid')
        <li class="active">
            <div class="icon">2</div> 
            <div class="title">seller approved</div>
        </li>
        <li class="active">
            <div class="icon">3</div>
            <div class="title"> buyer accept</div>
        </li>
        @endif
        <li class="">
            <div class="icon">@if($order->method=='post_paid')4 @else 2 @endif</div>
            <div class="title">On review</div>
        </li>
        <li class="">
            <div class="icon">@if($order->method=='post_paid')3 @else 5 @endif</div>
            <div class="title">On delivery</div>
        </li>
        <li class="">
            <div class="icon"> @if($order->method=='post_paid')6 @else 4 @endif </div>
            <div class="title">Delivered</div>
        </li>
</ul>

@elseif($order->status == 'completed')

                                    <ul class="process-steps">
                                            <li class="active">
                                                <div class="icon">1</div>
                                                <div class="title">Order placed</div>
                                            </li>
                                            @if($order->method=='post_paid')
                                            <li class="active">
                                                <div class="icon">2</div> 
                                                <div class="title">seller approved</div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">3</div>
                                                <div class="title">waiting buyer</div>
                                            </li>
                                            @endif
                                            <li class="active">
                                                <div class="icon">4</div>
                                                <div class="title">On delivery</div>
                                            </li>
                                            <li class="active">
                                                <div class="icon">5</div>
                                                <div class="title">Delivered</div>
                                            </li>
                                    </ul>

@endif