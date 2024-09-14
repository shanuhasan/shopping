$("#customer_div,#shipping_tr,#orderdiscount_tr,#grand_total_tr").hide();

grand_total=0;

$(document).ready(function (params) {

    $('#customer').val($('#customer').val()).select2({

        minimumInputLength: 1,

        data: [],

        initSelection: function (element, callback) {

            $.ajax({

                type: "get",

                async: false,

                url: site_url + "customers/getCustomers/",

                data:{"customer_id":$(element).val()},

                dataType: "json",

                success: function (data) {

                    $("#addIcon").toggleClass("fa-plus fa-edit");

                    callback(data[0]);

                }

            });

        },

        ajax: {

            url: site_url    + "customers/getCustomers/",

            dataType: 'json',

            // quietMillis: 15,

            data: function (term, page) {

                return {

                    term: term,

                    page: page,

                    limit: 10,

                };

            },

            results: function (data, page) {

                if (data.results != null) {

                    var more = (page * 10) < data.total_count;

                    return { results: data.results, more: more };

                } else {

                    return { results: [{ id: '', text: 'No Match Found' }] };

                }

            }

        }

    });



    $('#search_product').select2({

        minimumInputLength: 1,

        data: [],        

        ajax: {

            url: site_url + "products/findProducts/",

            dataType: 'json',

            // quietMillis: 5,

            data: function (term, page) {

                return {

                    term: term,

                    page: page,

                    limit: 10,

                };

            },

            results: function (data, page) {

                //console.log(data);

                if (data.results != null) {

                    var more = (page * 10) < data.total_count;

                    return { results: data.results, more: more };

                } else {

                    return { results: [{ id: '', text: 'No Match Found' }] };

                }

            }

        }

    });



    $("body").on("click","#customer_action",function () {

        $("#customer_div").toggle();

        $("#addIcon").toggleClass("fa-plus fa-minus");

    })



    

   



    $('#search_product').on('select2-selecting', function (data) {

        //$('#search_product').val('').trigger("change");

        $('#s2id_search_product .select2-chosen').text('').trigger("change");

        product_id = data.choice.id;

        item_id = data.choice.item_id;

        $.ajax({

            type: "get",

            async: false,

            url: site_url + "order/addTocart",

            data:{"product_id":product_id,'item_id':item_id},

            dataType: "json",

            success: function (data) {

                if (data.status==true) {

                    arrengeCart(data.cart);

                   

                }

                

            }

        });

    })



    $("body").on("change",".item_quantity",function (params) {

        val=$(this).val();

        product_id=$(this).closest("tr").attr("data-id");

        item_id=$(this).closest("tr").attr("data-item_id");

        $.ajax({

            type: "get",

            async: false,

            url: site_url + "order/addTocart",

            data:{"product_id":product_id,"quantity":val,'item_id':item_id},

            dataType: "json",

            success: function (data) {

                if (data.status==true) {

                    arrengeCart(data.cart);

                }

                

            }

        });

    })

    $("body").on("change","#order_discount,#shipping",function (params) {

        var grand_total=$("#cart_table").attr("data-grand_total")?parseFloat($("#cart_table").attr("data-grand_total")):0;

        if(grand_total <=0){
            return false;
        }

        grand_total-=$("#order_discount").val()?parseFloat($("#order_discount").val()):0;

        grand_total+=$("#shipping").val()?parseFloat($("#shipping").val()):0;

        $("#shipping_val").text($("#shipping").val()?"INR "+parseFloat($("#shipping").val()):0).show();

        $("#discount_val").text($("#order_discount").val()?"INR -"+parseFloat($("#order_discount").val()):0).show();

        $("#grand_total_val").text("INR "+grand_total).show();

    })



    $("body").on("click","#place_order",function (params) {

       if($("#cart_table tbody tr.cart_items").length ==0 || !$('#customer').val()){

           if(!$('#customer').val()){

                toastr.error("Please Select Customer");

           }else{

            toastr.error("Please add some service");

           }        

       }else{

        $("#order_form").submit();

       }

    })



    $("body").on("click",".remove_cart",function (params) {

        val=$(this).val();

        product_id=$(this).closest("tr").attr("data-id");

        $.ajax({

            type: "get",

            async: false,

            url: site_url + "order/removeCart",

            data:{"product_id":product_id},

            dataType: "json",

            success: function (data) {

                if (data.status==true) {

                    arrengeCart(data.cart);

                }

                

            }

        });

    })



    //Order Edit Js

    if($("#order_id").length >0){        

        order_id=$("#order_id").val();

        $.ajax({

            type: "POST",

            async: false,

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

            url: site_url + "order/getOrderItems",
            data:{"order_id":order_id},
            dataType: "json",
            success: function (data) {

                if (data.status==true) {

                    arrengeCart(data.cart);
                }

            }

        });

    }

})



function arrengeCart(data) {
    
    var items='';
    grand_total=0;
    $.each(data,function (product_id,item) {

        image='';
        if(item.image){
            image='<img src="'+asset+'uploads/service/'+item.image+'" width="60px">'
        }

        var item_status = '';

        item_status = `<select name="itme_status" class="item_status_change">
                <option value="pending_${item.order_item_id}"  ${item.order_item_status=='pending'?'selected':''}>Pending </option>
                <option value="ordered_${item.order_item_id}"  ${item.order_item_status=='ordered'?'selected':''}>Ordered</option>
                <option value="processing_${item.order_item_id}" ${item.order_item_status=='processing'?'selected':''}>Processing</option>
                 <option value="shipped_${item.order_item_id}" ${item.order_item_status=='shipped'?'selected':''}>Shipped</option>
                <option value="delivered_${item.order_item_id}" ${item.order_item_status=='delivered'?'selected':''}>Delivered</option>
                <option value="cancelled_${item.order_item_id}" ${item.order_item_status=='cancelled'?'selected':''}>Cancelled</option>
                <option value="returnpending_${item.order_item_id}" ${item.order_item_status=='return_pending'?'selected':''}>Return pending</option>
                <option value="returncomplete_${item.order_item_id}" ${item.order_item_status=='return_complete'?'selected':''}>Return complete</option>
            </select>`;

        price=item.service_price?item.service_price:0;
        if(item.sale_price){
            price=item.sale_price;
        }
        
         //console.log(item.quantity);
        
        var total=parseFloat(price)*parseFloat(item.quantity);
        grand_total+=total;
        // items+='<tr class="cart_items" id="product'+item.id+''+item.item_id+'" data-id="'+item.id+'" data-item_id="'+item.item_id+'"><td>'+image+'</td><td>'+item.vendor_name+'</td><td>'+item.service_name+'</td><td>'+price+'</td><td><input type="number" class="item_quantity" name="quantity['+item.id+']" value="'+item.quantity+'">' +" "+item.unit_value+ ' '+item.unit+'</td><td>INR '+total+'</td><td>'+item_status+'</td><td><a href="#" class="remove_cart"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>'
        items+='<tr class="cart_items" id="product'+item.id+''+item.item_id+'" data-id="'+item.id+'" data-item_id="'+item.item_id+'"><td>'+image+'</td><td>'+item.vendor_name+'</td><td>'+item.service_name+'</td><td>'+price+'</td><td><input type="number" class="item_quantity" name="quantity['+item.id+']" value="'+item.quantity+'"></td><td>INR '+total+'</td><td>'+item_status+'</td></tr>'

    })
    
    

    $("#cart_table").attr("data-grand_total",grand_total);

    grand_total-=$("#order_discount").val()?parseFloat($("#order_discount").val()):0;
    grand_total+=$("#shipping").val()?parseFloat($("#shipping").val()):0;

    $("#shipping_val").text($("#shipping").val()?"INR "+parseFloat($("#shipping").val()):0);
    $("#discount_val").text($("#order_discount").val()?"INR -"+parseFloat($("#order_discount").val()):0);
    $("#grand_total_val").text("INR "+grand_total);
    $("#shipping_tr,#orderdiscount_tr,#grand_total_tr").show();  
    $("#cart_table tbody").html(items);
}



$(document).ready(function(){

    $('.item_status_change').change(function(){
        
        var value = $(this).val();
        var res = value.split("_")[0];
        var msg=`${res} success`;
        toastr.success(msg);
        
        $.ajax({
            type: "POST",
            async: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: site_url + "order/item/status",
            data:{"value":value},
            // dataType: "json",
            success: function (data) {
                console.log('success');
                //window.location.reload(true);
            }
        });
        
    });
});







