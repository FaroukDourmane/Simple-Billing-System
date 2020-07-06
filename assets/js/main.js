$(document).ready(function(){

  // Close Ajax Box
  $(".closeBox").click(function(e){
    e.preventDefault();
    closeBox();
  });

  // Bind clicking outside ajax box
    $("body").click(function(e){
      if (e.target.id != 'ajaxBox' && e.target.id != 'addPackage' && $(e.target).parents('#ajaxBox').length == 0) {
        if ( $(".ajaxBox").hasClass("active") )
        {
          closeBox();
        }
      }
    });

  $(".addPackage").click(function(e){
    e.preventDefault();
    empty_box();

    $("body").addClass("disabled");
    $(".ajaxBox").addClass("active loading");

    var hidden_token = $("input[name='hidden_token']").val();

    $.post("ajax/operations.php", {action: "newPackageInitialize", token: hidden_token})
    .done(function(response){
      response = $.parseJSON(response);
      if ( response.type == "success" ){
        $(".ajaxBox").removeClass("loading");
        $(".ajaxBox .wrapper").html("");
      }
    });

  });

  function closeBox(){
    $(".ajaxBox").removeClass("active");
    $(".ajaxBox .wrapper").html("");
    $(".ajaxBox .ajaxTotal").text("0");
    $("body").removeClass("disabled");
  }

  // Functions
  function add_note(note)
  {
    $(".noteForm").addClass("loading");
    var hidden_token = $("input[name='hidden_token']").val();

    $.post("ajax/operations.php", {action: "addNote",note: note,token: hidden_token})
    .done(function(response){
      $(".noteForm").removeClass("loading");
      response = $.parseJSON(response);

      if (response.type == "success")
      {
        $(".noteForm input[name='note']").val("");
        $(".notes-container").prepend('<div class="item new" id="'+response.key+'"><div class="tools"> <a class="delete"> <img src="assets/svg/delete.svg" /> </a>  <a class="edit"> <img src="assets/svg/edit.svg" /> </a> </div><p>'+response.message+'</p>');
      }else{
        alert(response.message);
      }
    });
  }

  function add_service(service)
  {
    $(".newService").addClass("loading");
    var hidden_token = $("input[name='hidden_token']").val();

    $.post("ajax/operations.php", {action: "newService",service: service,token: hidden_token})
    .done(function(response){
      $(".newService").removeClass("loading");
      response = $.parseJSON(response);

      if (response.type == "success")
      {
        var service = response.message;
        $(".newService input[name='service']").val("");
        $(".ajaxBox .wrapper").prepend('<div class="item" id="'+response.key+'"><div class="tools"> <a class="delete"> <img src="assets/svg/delete.svg" /> </a>  <a class="edit"> <img src="assets/svg/edit.svg" /> </a> </div><p> '+service+' </p></div>');

        if ( $(".services-container .item."+response.reference).length > 0 )
        {
          $(".services-container .item."+response.reference+" ul").prepend("<li class='"+response.key+"'>"+service+"</li>");
        }else{
          $(".services-container").append('<div class="item '+response.reference+'" id="'+response.reference+'"><div class="loadingContainer"></div><div class="title"><p>'+services_package+'</p><div class="options"><a class="delete"> <img src="assets/svg/delete.svg" /> </a><a class="edit"> <img src="assets/svg/edit.svg" /> </a></div></div><img src="assets/svg/sale-tag.svg" class="tag" /><ul><li class="'+response.key+'">'+service+'</li></ul><div class="cost-container"><div class="top"><div class="cost"><b><i class="costNumber">0</i> TL</b><i>'+service_cost+'</i></div><div class="discount"><b><i class="discountNumber">0</i> TL</b><i>'+discount+'</i></div></div><div class="bottom"><p>'+total+'</p><p><span class="totalNumber">0</span> TL</p></div></div></div>');
        }

      }else{
        alert(response.message+' key => '+response.key);
      }
    });
  }

  function empty_box() {
    $(".ajaxBox input[type='text']").val("");
    $(".ajaxBox input[type='number']").val("0");
  }

  function open_box() {
    $("body").addClass("disabled");
    $(".ajaxBox").addClass("active loading");
  }


  // Bind operations
  $(".noteForm").submit(function(e){
    e.preventDefault();
    var note = $(".noteForm input[name='note']").val();
    add_note(note);
  });

  // Adding new service to package
  $(document).on("submit",".newService",function(e){
    e.preventDefault();
    var service = $(".newService input[name='service']").val();
    add_service(service);
  });



  // Count Total cost
  function count_total_cost ()
  {
    var  cost = parseInt($(".ajaxBox input[name='service_cost']").val());
    var  discount = parseInt($(".ajaxBox input[name='service_discount']").val());
    var result = 0;

    if ( discount <= cost )
    {
      result = cost-discount;
    }

    $(".ajaxBox .ajaxTotal").html(result);
  }

  $(".ajaxBox input[name='service_cost']").keyup(function(){
    count_total_cost();
  });

  $(".ajaxBox input[name='service_discount']").keyup(function(){
    count_total_cost();
  });


  // DELETE PACKAGE
  $(document).on("click",".services-container .options .delete",function(e){
    e.preventDefault();
    var id = $(this).parents(".item").attr("id");
    var item = $(".services-container .item."+id);
    $(item).addClass("loading");
    var hidden_token = $("input[name='hidden_token']").val();

    $.post("ajax/operations.php", {action: "deletePackage", token: hidden_token, id: id})
    .done(function(response){
      response = $.parseJSON(response);

      if ( response.type == "success" )
      {
        $(item).remove();
      }else{
        alert(response.message);
      }
    });
  });
  // END DELETE PACKAGE


  // EDIT Package
  $(document).on('click','.services-container .options .edit',function(e){
    e.preventDefault();

    open_box();
    var id = $(this).parents(".item").attr("id");
    var item = $(".services-container .item."+id);

    //$(item).addClass("loading");

    var hidden_token = $("input[name='hidden_token']").val();

    $.post("ajax/operations.php", {action: "editPackage",id: id, token: hidden_token})
    .done(function(response){
      response = $.parseJSON(response);
      if (response.type == "success")
      {
        var services = $.parseJSON(response.message);

        $.each(services, function(k,v){
          $(".ajaxBox .wrapper").append('<div class="item" id="'+k+'"><div class="loadingContainer"></div><div class="tools"> <a class="delete"> <img src="assets/svg/delete.svg" /> </a>  <a class="edit"> <img src="assets/svg/edit.svg" /> </a> </div><p>'+v+'</p></div>')
        });


        var costs = $.parseJSON(response.reference);
        $(".ajaxBox input[name='service_cost']").val(costs.cost);
        $(".ajaxBox input[name='service_discount']").val(costs.discount);
        $(".ajaxBox .ajaxTotal").text(costs.total);

        $(".ajaxBox").removeClass("loading");
      }else{
        alert(response.message);
        //$(item).removeClass("loading");
        closeBox();
      }
    });
  });
  // END Package


  // DELETE NOTE
  $(document).on("click",".notes-container .item .delete",function(e){
    e.preventDefault();
    var id = $(this).parents(".item").attr("id");
    var item = $(this).parents(".item");
    var hidden_token = $("input[name='hidden_token']").val();

    $(item).addClass("loading");
    $.post("ajax/operations.php", {action: "deleteNote", id: id, token: hidden_token})
    .done(function(response){
      response = $.parseJSON(response);

      if ( response.type == "success" )
      {
        $(item).remove();
      }else{
        $(item).removeClass("loading");
        alert(response.message);
      }
    });
  });
  // END DELETE NOTE

  // EDIT NOTE
  $(document).on("click",".notes-container .item .edit",function(e){
    var container = $(this).parents(".item");
    var value = $(container).find("p").text();
    create_fake_input(container, value);
  });
  // END EDIT NOTE

  $(document).on('click','.notes-container .item .fakeForm .cancel',function(e){
    var container = $(this).parents(".item");
    $(this).parents(".fakeForm").remove();
    $(container).removeClass("loading");
  });

  $(document).on('submit','.notes-container .item .fakeForm',function(e){
    e.preventDefault();

    var container = $(this).parents(".item");
    $(container).addClass("loading");
    var hidden_token = $("input[name='hidden_token']").val();
    var key = $(container).attr("id");
    var note = $(this).find("input[name='note']").val();

    $.post("ajax/operations.php", {action: "editNote",token: hidden_token,key: key, note: note})
    .done(function(response){
      response = $.parseJSON(response);
      $(container).removeClass("loading");

      if ( response.type == "success" )
      {
        $(container).find("p").html(response.message);
        $(container).find(".fakeForm").remove();
      }else{
        alert(response.message);
      }
    });
  });

  function create_fake_input(container, value){
    var form = '<form class="fakeForm" action="" method="post"><input type="text" name="note" value="'+value+'" placeholder="'+note+'" required /><div class="options"><a class="cancel">'+cancel+'</a><label><input type="submit" style="display:none;"><a class="apply">'+apply+'</a></label></div></form>';
    $(container).append(form);
  }


  // Approving Package
  $(".ajaxBox .approveAjax").click(function(e){
    var box = $(".ajaxBox");
    $(box).addClass("loading");
    var hidden_token = $("input[name='hidden_token']").val();
    var cost = $(".ajaxBox input[name='service_cost']").val();
    var discount = $(".ajaxBox input[name='service_discount']").val();

    $.post("ajax/operations.php", {action: "approvePackage",token: hidden_token,cost: cost, discount: discount})
    .done(function(response){
      response = $.parseJSON(response);

      if (response.type = "success")
      {
        $(".services-container .item."+response.reference+" .cost-container .costNumber").text(cost);
        $(".services-container .item."+response.reference+" .cost-container .discountNumber").text(discount);

        if (cost > discount)
        {
          total = cost-discount;
        }else{
          total = 0;
        }

        $(".services-container .item."+response.reference+" .cost-container .totalNumber").text(total);
        $(".total-container .servicesTotal").text(response.total_number);

        closeBox();
      }else{
        alert(response.message);
      }

      $(box).removeClass("loading");
    });
  });



  // remove service
  $(document).on('click','.ajaxBox .wrapper .item .delete',function(e){
    var container = $(this).parents(".item");
    var id = $(container).attr("id");
    var hidden_token = $("input[name='hidden_token']").val();
    $(container).addClass("loading");

    $.post("ajax/operations.php", {action: "deleteService",token: hidden_token, id: id})
    .done(function(response){
      response = $.parseJSON(response);
      if (response.type == "success"){
        $(container).remove();
        $(".services-container .item."+response.key+" ul li."+id).remove();
      }else{
        alert(response.message);
      }

      $(container).removeClass("loading");
    });
  });


  // edit service
  $(document).on('click','.ajaxBox .wrapper .item .edit',function(e){
    var container = $(this).parents(".item");
    var value = $(container).find("p").text();
    create_fake_input(container, value);
  });

  $(document).on('submit','.ajaxBox .wrapper .item .fakeForm',function(e){
    e.preventDefault();
    var container = $(this).parents(".item");
    var id = $(container).attr("id");
    $(container).addClass("loading");

    var value = $(this).find("input[name='note']").val();

    var hidden_token = $("input[name='hidden_token']").val();

    $.post("ajax/operations.php",{action: "editService", token: hidden_token, id: id, value: value})
    .done(function(response){
      response = $.parseJSON(response);

      if ( response.type == "success" )
      {
        $(container).find("p").text(value);
        $(".services-container .item."+response.key+" ul li."+id).text(value);
        $(container).find(".fakeForm").remove();
      }else{
        alert(response.message);
      }

      $(container).removeClass("loading");
    });
  });

  $(document).on('click','.ajaxBox .wrapper .item .fakeForm .cancel',function(e){
    var container = $(this).parents(".item");
    $(container).find(".fakeForm").remove();
  });


  // Reset everything
  $(".ajaxReset").click(function(e){
    $("body").addClass("disabled loading");
    var hidden_token = $("input[name='hidden_token']").val();

    $.post("ajax/operations.php",{action: "resetAll", token: hidden_token})
    .done(function(response){
      response = $.parseJSON(response);
      if (response.type == "success")
      {
        $(".services-container").html("");
        $(".notes-container").html("");
        $(".mainForm input").val("");
        $(".total-container .servicesTotal").text("0");

        $("body").removeClass("loading disabled");
      }else{
        alert(response.message);
      }
    });
  });


  $(".ajaxPreview").click(function(e){

    var company_name = $(".mainForm input[name='company_name']").val();
    var company_representive = $(".mainForm input[name='company_representive']").val();
    var phone_number = $(".mainForm input[name='phone_number']").val();
    var gsm_number = $(".mainForm input[name='gsm_number']").val();
    var hidden_token = $("input[name='hidden_token']").val();

    window.location = 'printable.php?company_name='+company_name+'&company_representive='+company_representive+'&phone_number='+phone_number+'&gsm_number='+gsm_number+'&token='+hidden_token;
  });

});
