var provinces = {
    "A": {
        "安徽":["合肥","芜湖","蚌埠","淮南","马鞍山","淮北","铜陵","安庆","黄山","滁州","阜阳","宿州","巢湖","六安","亳州","池州","宣城"]
    },
    "B": {
        "北京": ["北京"]
    },
    "C": {
        "重庆":["重庆"]
    },
    "F": {
        "福建":["福州","厦门","莆田","三明","泉州","漳州","南平","龙岩","宁德"]
    },
    "G": {
        "甘肃":["兰州","嘉峪关","金昌","白银","天水","武威","张掖","平凉","酒泉","庆阳","定西","陇南","临夏","甘南"],
        "广东":["广州","深圳","珠海","汕头","韶关","佛山","江门","湛江","茂名","肇庆","惠州","梅州","汕尾","河源","阳江","清远","东莞","中山","潮州","揭阳","云浮"],
        "广西":["南宁","柳州","桂林","梧州","北海","防城港","钦州","贵港","玉林","百色","贺州","河池","来宾","崇左"],
        "贵州":["贵阳","六盘水","遵义","安顺","铜仁","黔西南","毕节","黔东南","黔南"]
    },
    "H": {
        "海南":["海口","三亚"],
        "河北":["石家庄","唐山","秦皇岛","邯郸","邢台","保定","张家口","承德","沧州","廊坊","衡水"],
        "河南":["郑州","开封","洛阳","平顶山","安阳","鹤壁","新乡","焦作","濮阳","许昌","漯河","三门峡","南阳","商丘","信阳","周口","驻马店"],
        "黑龙江":["哈尔滨","齐齐哈尔","鸡西","鹤岗","双鸭山","大庆","伊春","佳木斯","七台河","牡丹江","黑河","绥化","大兴安岭地区"],
        "湖北":["武汉","黄石","十堰","宜昌","襄樊","鄂州","荆门","孝感","荆州","黄冈","咸宁","随州","恩施","神农架林区"],
        "湖南":["长沙","株洲","湘潭","衡阳","邵阳","岳阳","常德","张家界","益阳","郴州","永州","怀化","娄底","湘西"]
    },
    "J": {
        "吉林":["长春","吉林","四平","辽源","通化","白山","松原","白城","延边"],
        "江苏":["南京","无锡","徐州","常州","苏州","南通","连云港","淮安","盐城","扬州","镇江","泰州","宿迁"],
        "江西":["南昌","景德镇","萍乡","九江","新余","鹰潭","赣州","吉安","宜春","抚州","上饶"]
    },
    "L": {
        "辽宁":["沈阳","大连","鞍山","抚顺","本溪","丹东","锦州","营口","阜新","辽阳","盘锦","铁岭","朝阳","葫芦岛"]
    },
    "N": {
        "内蒙古":["呼和浩特","包头","乌海","赤峰","通辽","鄂尔多斯","呼伦贝尔","巴彦淖尔","乌兰察布","兴安盟","锡林郭勒盟","阿拉善盟"],
        "宁夏":["银川","石嘴山","吴忠","固原","中卫"]
    },
    "Q": {
        "青海":["西宁","海东","海北","黄南","海南","果洛","玉树","海西"]
    },
    "S": {
        "山东":["济南","青岛","淄博","枣庄","东营","烟台","潍坊","济宁","泰安","威海","日照","莱芜","临沂","德州","聊城","滨州","菏泽"],
        "山西":["太原","大同","阳泉","长治","晋城","朔州","晋中","运城","忻州","临汾","吕梁"],
        "陕西":["西安","铜川","宝鸡","咸阳","渭南","延安","汉中","榆林","安康","商洛"],
        "上海":["上海"],
        "四川":["成都","自贡","攀枝花","泸州","德阳","绵阳","广元","遂宁","内江","乐山","南充","眉山","宜宾","广安","达州","雅安","巴中","资阳","阿坝","甘孜","凉山"]
    },
    "T": {
        "天津": ["天津"]
    },
    "X": {
        "西藏":["拉萨","昌都","山南","日喀则","那曲","阿里","林芝"],
        "新疆":["乌鲁木齐","克拉玛依","吐鲁番","哈密","昌吉","博尔塔拉","巴音郭楞","阿克苏","克孜勒苏","喀什","和田","伊犁","塔城","石河子","阿拉尔市","图木舒克","五家渠"]
    },
    "Y": {
        "云南":["昆明","曲靖","玉溪","保山","昭通","丽江","思茅","临沧","楚雄","红河","文山","西双版纳","大理","德宏","怒江","迪庆"]
    },
    "Z": {
        "浙江":["杭州","宁波","温州","嘉兴","湖州","绍兴","金华","衢州","舟山","台州","丽水"]
    }
};

(function ($, win, doc) {
    var CityPicker = function (el, options) {
        this.el = $(el);
        this.options = options;
        this.provinces = provinces;
        this.pro = null;
        this.city = null;
        this.elType = this.el.is('input');

        this.init();
    };

    var p = CityPicker.prototype;

    p.init = function () {
        this.initEvent();
        this.preventPopKeyboard();

    };

    p.preventPopKeyboard = function () {
        if (this.elType) {
            this.el.prop("readonly", true);
        }
    };

    p.initEvent = function () {
        this.el.on("focus", function (e) {
            var pickerBox = $(".picker-box");
            if (pickerBox[0]) {
                pickerBox.show();
            } else {
                this.create();
            }
        }.bind(this));
    };

    p.create = function () {
        this.createCityPickerBox();
        this.createProList();
        this.proClick();
        this.createNavBar();
        this.navEvent();
    };

    p.createCityPickerBox = function () {
        var proBox = "<div class='picker-box'></div>";
        $("body").append(proBox);
    };

    p.createProList = function () {
        var provinces = this.provinces;
        var proBox;
        var dl = "";
        for (var letterKey in provinces) {
            var val = provinces[letterKey];
            if (provinces.hasOwnProperty(letterKey)) {
                var dt = "<dt id='" + letterKey + "'>" + letterKey + "</dt>";
                var dd = "";
                for (var proKey in val) {
                    if (val.hasOwnProperty(proKey)) {
                        dd += "<dd data-letter=" + letterKey + ">" + proKey + "</dd>";
                    }
                }
                dl += "<dl>" + dt + dd + "</dl>";
            }
        }

        proBox = "<section class='pro-picker'>" + dl + "</section>";

        $(".picker-box").append(proBox);
    };

    p.createCityList = function (letter, pro) {
        var cities = this.provinces[letter][pro];
        var ul, li = "";
        cities.forEach(function (city, i) {
            li += "<li>" + city + "</li>";
        });

        ul = "<ul class='city-picker'>" + li + "</ul>";
        $(".picker-box").find(".city-picker").remove().end().append(ul);

        this.cityClick();
    };

    p.proClick = function () {
        var that = this;
        $(".pro-picker").on("click", function (e) {
            var target = e.target;
            if ($(target).is("dd")) {
                that.pro = $(target).html();
                var letter = $(target).data("letter");
                that.createCityList(letter, that.pro);

                $(this).hide();
            }
        });
    };

    p.cityClick = function () {
        var that = this;
        $(".city-picker").on("click", function (e) {
            var target = e.target;
            if ($(target).is("li")) {
                that.city = $(target).html();
                var cityHtml;
                console.log(that.city);
                if (that.elType) {
                    that.el.val(that.city);
                    cityHtml=that.city;
                    $(".city-search-box").html(cityHtml);
                    console.log(cityHtml);
                    var cityWidth=$(".city-search").outerWidth(true)+10;
                    var textWidth=$(".search-area").width();
                    var textWidths=textWidth-cityWidth;
                    var htmlSize=parseInt($('html').css('font-size'))
                    $(".city-text").css({'width':textWidths/htmlSize+'rem'});
                    console.log(htmlSize)
                    // $(".city-text").width($(".search-area").width()-$(".city-search").width()-50);
                } else {
                    that.el.html(that.city);
                }

                $(".picker-box").hide();
                $(".pro-picker").show();
                $(this).hide();
                 // $(".city-search").html(that.city);
            }
        });
    };

    p.createNavBar = function () {
        var str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var arr = str.split("");
        var a = "";
        arr.forEach(function (item, i) {
            a += '<a href="#' + item + '">' + item + '</a>';
        });

        var div = '<div class="navbar">' + a + '</div>';

        $(".picker-box").append(div);
    };

    p.navEvent = function () {
        var that = this;
        var navBar = $(".navbar");
        var width = navBar.find("a").width();
        var height = navBar.find("a").height();
        navBar.on("touchstart", function (e) {
            $(this).addClass("active");
            that.createLetterPrompt($(e.target).html());
        });

        navBar.on("touchmove", function (e) {
            e.preventDefault();
            var touch = e.originalEvent.touches[0];
            var pos = {"x": touch.pageX, "y": touch.pageY};
            var x = pos.x, y = pos.y;
            $(this).find("a").each(function (i, item) {
                var offset = $(item).offset();
                var left = offset.left, top = offset.top;
                if (x > left && x < (left + width) && y > top && y < (top + height)) {
                    location.href = item.href;
                    that.changeLetter($(item).html());
                }
            });
        });

        navBar.on("touchend", function () {
            $(this).removeClass("active");
            $(".prompt").hide();
        })
    };

    p.createLetterPrompt = function (letter) {
        var prompt = $(".prompt");
        if (prompt[0]) {
            prompt.show();
        } else {
            var span = "<span class='prompt'>" + letter + "</span>";
            $(".picker-box").append(span);
        }
    };


    p.changeLetter = function (letter) {
        var prompt = $(".prompt");
        prompt.html(letter);
    };


    $.fn.CityPicker = function (options) {
        return new CityPicker(this, options);
    }
}(window.jQuery, window, document));