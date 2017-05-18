$(function(){
	$('.nav_switch a').click(function(e){
		e.preventDefault();
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$('#nav').removeClass('active');
			$('#content').removeClass('active');
		}else{
			$(this).addClass('active');
			$('#nav').addClass('active');
			$('#content').addClass('active');
		}
	})
	
	$('#nav').find('dl').each(function(){
		var $this = $(this);
		$this.find(' > a').click(function(e){
			e.preventDefault();
			if($this.hasClass('active')){
				$this.removeClass('active');
			}else{
				$this.addClass('active');
			}
		})
	})
	
	$('.layerBox').each(function(){
		var $this = $(this);
		$this.find('.close_bt').click(function(e){
			e.preventDefault();
			$this.removeClass('show');
		})
	})
	
	var skin = function(name){
		var skin_name = [];
		$('.skin_sel li').each(function(){
			var name = $(this).find('a').data('skin');
			skin_name.push(name);
		})
		var skinColor = function(_class,_value){
			var className = _class.attr('class');
			className = className.replace(_value,'');
			_class.attr('class',className);
			_class.removeClass(skin_name.join(' '));
			var c = _value+name;
			_class.addClass(c);
		}
		$('.skin_bg').each(function(){
			var $this = $(this);
			skinColor($this,'skin_bg_');
		})
		$('.skin_color').each(function(){
			var $this = $(this);
			skinColor($this,'skin_color_');
		})
		$('.table_skin').each(function(){
			var $this = $(this);
			skinColor($this,'table_skin_');
		})
		$('.page_skin').each(function(){
			var $this = $(this);
			skinColor($this,'page_skin_');
		})
		$('.step_nav_skin').each(function(){
			var $this = $(this);
			skinColor($this,'step_nav_skin_');
		})
		$('.selectBox_skin').each(function(){
			var $this = $(this);
			skinColor($this,'selectBox_skin_');
		})
		$('.report_nav_skin').each(function(){
			var $this = $(this);
			skinColor($this,'report_nav_skin_');
		})
		$('.skin_sel li').removeClass('active');
		$('.skin_sel').find('span.'+name).parents('li').addClass('active');
	}
	
	var skin_cookie = Cookies.get('myskin');
	if(skin_cookie == null){
		skin('blue');
	}else{
		skin(skin_cookie);
	}
	$('.skin_sel li').each(function(){
		var $this = $(this);
		$(this).find('a').click(function(e){
			e.preventDefault();
			var name = $(this).data('skin');
			Cookies.set("myskin",name,{expires:30});
			skin(name);
		})
	})
	
	var hintBox = function(){
		$('.hintBox').each(function(){
			var $this = $(this);
			var h1 = $this.find(' > h3.title').height();
			var h2 = $this.find('.con').height() + parseInt($this.find('.con').css('padding-top')) + parseInt($this.find('.con').css('padding-bottom'));
			$this.height(h1+h2);
		})
	}
	setTimeout(hintBox(),500);
	
	$('.selectBox').each(function(){
		var $this = $(this);
		$this.find('p.selectBox_text').click(function(){
			if($this.hasClass('active')){
				$this.removeClass('active');
			}else{
				$this.addClass('active');
			}
		})
		$this.find('ul.selectBox_list li').each(function(){
			$(this).click(function(){
				var txt = $.trim($(this).text());
				$this.find('p.selectBox_text span.text').text(txt);
				$this.find('.selectBox_value').val(txt);
				$this.find('ul.selectBox_list li').removeClass('active');
				$(this).addClass('active');
				$this.removeClass('active');
			})
		})
	})
	$(document).click(function(e){
		if($(e.target).closest('.selectBox').length == 0){
			$('.selectBox').removeClass('active');
		}
	})
	
	$('.uploadBox').each(function(){
		$(this).find('a').click(function(e){
			e.preventDefault();
			$(this).siblings('input').click();
		})
	})
	
})
