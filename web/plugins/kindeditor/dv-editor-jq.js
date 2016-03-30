
KindEditor.ready(function(KE) {
    var uploadimg='/upload/uploadimage';
    var imgmanager='/upload/manageimages';
    KE.create('.dv-editor', {
        uploadJson : uploadimg,
        fileManagerJson : imgmanager,
        allowFileManager : true,
        width: '100%',
        height : "200px",
        minWidth: "300",
        items : ['fontname', 'fontsize',  'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat',  'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist',  'emoticons', 'image', 'link', 'source', 'fullscreen']
    });

    //<input type="text" class="hide" readonly name="img" value="" /><input type="button" class="btn btn-default dv-uploadimg" module="image" value="选择图片" />
    var $thumb_tpl='<img class="thumb_img" src="{url}" style="max-height: 100px;max-width:200px">';
    var editor = KE.editor({
        themeType: "simple",
        uploadJson : uploadimg,
        fileManagerJson : imgmanager,
        allowFileManager : true
    });
    $('.dv-uploadimg').live('click',function(e) {
        var module;
        if($(this).attr('module')){
            module=$(this).attr('module');
        }else{
            module=self.location.href.split( "/" )[3]
        }

        editor.uploadJson=uploadimg+'?module='+module;
        editor.loadPlugin('dv-image', function() {
            var $input=$(e.target).prevAll("input[type=text]");
            var $thumb=$(e.target).prevAll(".thumb_img:first");
            var curEditorDialog = editor.plugin.imageDialog({
                imageUrl : $input.val(),
                clickFn : function(url, title, width, height, border, align) {
                    $input.val(url);
                    $input.trigger('change');
                    $input.trigger('focusout');
                    if ($thumb.length == 0) {
                        $input.before($thumb_tpl.replace('{url}',url))
                    } else {
                        $thumb.attr("src", url);
                    }
                    editor.hideDialog();
                }
            });
            
            //add by ryq 上传图片弹出框位置居中显示
            function resetPos(curDialog) {
                var left, top;
                var $curDialogs = $(curDialog.div);
                if(curDialog ){
                    //var pageSidebar = $('.page-sidebar');
                    //padding-left:225px
                    //debugger;
                    // var clwidth = $curDialogs.width(),
                    //     defWidth = parent.document.documentElement.clientWidth - $curDialogs.width(),
                    //     defWidth2 = (parent.document.documentElement.clientWidth - $curDialogs.width()) / 2,
                    //     setwidth = (defWidth<=clwidth) ? clwidth/2 : defWidth2,

                    var setwidth = (parent.document.documentElement.clientWidth - $curDialogs.width()) / 2,
                    //     setheight = ($(parent.window).height() - parent.document.documentElement.clientHeight) / 2;
                    //var setwidth = (parent.document.documentElement.clientWidth - $curDialogs.width()) / 2,
                        //setheight = (parent.document.documentElement.clientHeight - $curDialogs.height()) / 2;

                        clheight = $curDialogs.height(),
                        defHeight = parent.document.documentElement.clientHeight - $curDialogs.height(),
                        defHeight2 = (parent.document.documentElement.clientHeight - $curDialogs.height()) / 2,
                        //setheight = (defHeight<=clheight) ? clheight/2 : defHeight2;
                        setheight = (defHeight<=0) ? 0 : defHeight2;
                        left = setwidth;
                        //left = $(parent.document).scrollLeft()>0 ? setwidth+$(parent.document).scrollLeft() : setwidth;
                        top = $(parent.document).scrollTop()>0 ? setheight+$(parent.document).scrollTop() : setheight;
                
                    $curDialogs.css({
                        top: top + "px",
                        left: left + "px"
                    });
                }
                
            }
            $(parent.document).scrollTop(0);//初始化scrollTop
            resetPos(curEditorDialog);

            $(parent.window).scroll(function () {
                resetPos(curEditorDialog);
                return false;
            });
            $(parent.window).on("resize", function () {   
                resetPos(curEditorDialog);
            });

            // console.log("App:");
            // console.log(App);
            // if (App.getResize()) {
            //     editor.dialogs[0].pos();
            //         //App.getResize();
            // }

            //add by ryq 上传图片弹出框位置居中显示 end

        });
    });
    $('.dv-uploadimg').each(function(){
        var $input = $(this).prevAll("input[type=text]");
        var $thumb=$(this).prevAll(".thumb_img:first");
        if($input.val().length>1){
            if ($thumb.length == 0) {
                $input.before($thumb_tpl.replace('{url}',$input.val()))
            } else {
                $thumb.attr("src", $input.val());
            }
        }
    });

});