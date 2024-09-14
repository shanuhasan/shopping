function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
    	$('.viewimg').css('display','block');
      $('#v_img').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#view_img").change(function() {
  readURL(this);
})

function readURL1(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
    	$('.viewimg1').css('display','block');
      $('#v_img1').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#view_img1").change(function() {
  readURL1(this);
})

function readURL2(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
    	$('.viewimg2').css('display','block');
      $('#v_img2').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#view_img2").change(function() {
  readURL2(this);
})