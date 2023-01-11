 $(document).ready(function () {
//     $(".select2").select2({
//         placeholder: "e.g. Last Week/ Last month",
//         allowClear: true,
//     });

   

     /* **** Add Remove Class **** */
    $(".board-block-wrp .dropdown .dropdown-toggle").on("click", function () {
        $(".board-block-wrp .dropdown").toggleClass("show-dropdown");
    });
    /* **** End Add Remove Class **** */ 

    /* ** Add class Check ** */
    $(".board-block-wrp .dropdown .dropdown-menu .dropdown-item").click(function (e) {
        var cb = $(this).find(":checkbox")[0];
        if (e.target != cb) cb.checked = !cb.checked;
        $(this).addClass("selected", cb.checked);
    });


    $('.pack-card .card-btn a').click(function(){
        $(this).toggleClass('active');
    });

   

    /* **** Add Remove Class **** */
    $(".pack-card .card-info ul li a").on("click", function () {
        $(".pack-card").toggleClass("show-information");
    });
    /* **** End Add Remove Class **** */ 

    // $(".pack-card .card-info ul li a").on("click", function () {
    //     $(".card-information").removeClass("show-information");
    // });


    $(".view_details").on("click", function () {
        var aa = $(this).closest('.card-info');
        console.log(aa);
        $(this).closest('.card-info').next().next().addClass("show-information");
    });
    $(".hide-info").on("click", function () {
        $(this).parent().removeClass("show-information");
    });

    $(".view_details").on("click", function () {
         $(".card-information").removeClass("show-information");
        $(this).closest('.card-info').next().next().addClass("show-information");
    });




    /* **** mobile Add Remove Class **** */
    $(".togglebar").on("click", function () {
        $(".dash-sidebar").toggleClass("show-sidebar");
    });
    /* **** End mobile Add Remove Class **** */

    /* **** mobile Add Remove Class **** */
    $(".sidebar-toggle").on("click", function () {
        $(".dash-sidebar").toggleClass("show-sidebar");
        $(".sidebar-toggle").toggleClass("show-sidebar");
        $(".main-wrpper").toggleClass("show-sidebar");
        $("header").toggleClass("show-sidebar");
    });
    /* **** End mobile Add Remove Class **** */

     $('.card-information ul.btn-box li a.time-btn').click(function(){
        $(this).toggleClass('active');
    });    


        
});
