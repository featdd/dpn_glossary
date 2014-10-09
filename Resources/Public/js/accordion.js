$(window).load(function() {

	$('.term-details .description h3').on("click", function(e) {
		e.preventDefault();
		if (!$(this).parents(".description").hasClass("open")) {
			$(".term-details .description.open .text").slideUp("slow");
			$(".term-details .description.open").removeClass("open");

			$(this).parents(".description").addClass("open");
			$(this).parents(".description").find(".text").slideDown("slow");
		} else {
			$(".term-details .description.open .text").slideUp("slow");
			$(".term-details .description.open").removeClass("open");
		}
	});

});