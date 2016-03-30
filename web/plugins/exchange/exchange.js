

/**
 * 命名空间X
 * @nampespace X
 */
var X = window.X = X || {};

//闭包
(function($){

   /**
    * @module 组件名称，Exchange
    * @author mabaoyue

    * @param  {String} 可供jquery直接获取的id，例如 "#foo"(必填)
    * @param  {Object} Exchange的配置参数(可选)
    * @constructor
    */

    var Exchange = function(el, opts) {

        //检测el是否存在， 不存在就返回
        if(!$(el).length) {
            return;
        }

        /**
         * 组件的配置项，供其他方法使用
         * @private
         * @type {Object}
         */

        this._defaultSettings = {
            imgParentSelector: "li",
            maskSelector: ".ex-control",
            hideClass: "none",
            dealSelector: "span",
            hoverClass : "hover",
            deleteClick : ""
        };

        /**
         * 组件被应用的元素，供其他方法使用
         * @private
         * @type {jQueryDom}
         */
        this._el = $(el);


        /**
         * 组件的配置项，供其他方法使用
         * @private
         * @type {Object}
         */
        this._opts =   $.extend( {} ,this._defaultSettings , opts );

        /**
         * @property {String} 图片所在父级选择器
         */

        this.imgParentSelector =  this._opts.imgParentSelector;

        /**
         * @property {jquery object} 图片所在父级DOM元素
         */

        this.imgs =  this._el.find(this.imgParentSelector);

        /**
         * @property {String} hideClass 隐藏显示的class名称
         */

        this.hideClass =  this._opts.hideClass;

        /**
         * @property {String} maskSelector 图片遮罩层选择器
         */

        this.maskSelector =  this._opts.maskSelector;

        /**
         * @property {String} dealSelector 左移动、右移动和删除操作的选择器
         */

        this.dealSelector =  this._opts.dealSelector;
        /**
         * @property {String} hoverClass 鼠标悬浮图片时增加class
         */

        this.hoverClass =  this._opts.hoverClass;



        /**
         * 调用组件的初始化
         */
        this.init();
    };

    $.extend(Exchange.prototype, {

        /**
         * 组件初始化方法,比如绑定事件之类
         * @method init
         * @return none
         */
        init: function() {
            this.render();
        },

        /**
         * 组件ui的展现
         *
         * @method render
         * @return none
         */
        render: function() {
                var self = this,
                $t = this._el,
                sel = this.imgParentSelector,
                deal = this.dealSelector,
                hideClass = this.hideClass,
                hoverClass = this.hoverClass;
                $t.on("mouseenter", sel , function() {
                    var $img = $(this);

                    self._showPos($img);
                });
                $t.on("mouseleave", sel , function() {
                    $(this).removeClass(hoverClass);
                    $(this).find(self.maskSelector).addClass(hideClass);
                });
                $t.on("click", deal, function(e) {
                    e.preventDefault();
                    var $v = $(this),
                        $img = $v.closest(sel),
                        control = $(this).data("control");
                    self._showPos($img);
                    if (control == "left") {
                        $img.insertBefore($img.prev());
                    } else if (control == "right") {
                        $img.insertAfter($img.next());
                    } else if (control == "delete") {
                        !!self._opts.deleteClick && !!self._opts.deleteClick();
                        $img.remove();   
                    }
                    self.serialize();//new add by ryq
                    //!!self._opts.callback && self._opts.callback($v);
                });
        },
        serialize : function(){
            if(!!this._opts.serialize){
                this._opts.serialize();
                return;
            }
            var $t = this._el,
            $input = $t.find(".seria"),
            arr = [],
            name = null;
            $t.find(this.imgParentSelector).each(function(){
                name = $(this).find("img").data("name");
                if(!!name){
                    arr.push(name);

                }

            });
            $input.val(arr.join(","));
            arr = null;
        },
        /**
         * 交换图片位置
         *
         * @private 私有方法
         * @method showPos
         * @return none
         */

        _showPos: function($img) {
                var self = this,
                $t = this._el,
                sel = this.imgParentSelector,
                mask = this.maskSelector,
                deal = this.dealSelector,
                hoverClass = this.hoverClass,
                hideClass = this.hideClass;
                $img.addClass(hoverClass);
                $img.siblings().each(function() {
                    $(this).find(mask).addClass(hideClass);
                    $(this).removeClass(hoverClass);
                });
                $img.find(mask).removeClass(hideClass);
                $img.find(deal).each(function() {
                    $(this).removeClass(hideClass);
                });
                if(!$t.find(sel).index($img) && ($t.find(sel).index($img) == $t.find(sel).length - 1)){
                    $img.find(deal).eq(0).addClass(hideClass);
                    $img.find(deal).eq(1).addClass(hideClass);
                }else if (!$t.find(sel).index($img)) {
                    $img.find(deal).eq(0).addClass(hideClass);
                } else if ($t.find(sel).index($img) == $t.find(sel).length - 1) {
                    $img.find(deal).eq(1).addClass(hideClass);
                }
        }


    })


    /**
     * 将组件挂载到X上, 项目中调用: X.Exchange

     *
         X.Exchange("#ul1");
     *
     * @extends X 扩展自X
     * @class  Exchange开发指引

     */

    X.Exchange = function(el,opts) {
        return  new Exchange(el,opts);
    }

})(jQuery)