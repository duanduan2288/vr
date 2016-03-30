<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption cap-head">
                    <i class="icon icon-bell"></i>
                    <a>信息管理&nbsp;&nbsp;<i class="icon-angle-right"></i></a>
                    <a href="#">消息详情</a>
                </div>
                <div class="tools">
                    <a href="javascript:location.reload();"><i class="icon-refresh"></i>刷新</a>&nbsp;&nbsp;
                    <a href="javascript:history.go(-1);"><i class="icon-reply"></i>返回</a>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /*#title{text-align: center;line-height: 30px;}*/
    .center{text-align: center;}
    .center img{margin: 0 auto;}
    .news-item-page{line-height: 26px;}
</style>

<div class="row">
    <!-- <div class="col-md-12 news-page blog-page">
        <div class="row">
            <div class="col-md-12 blog-tag-data" style="max-width: 960px;">
                <h3 id="title"><?php //echo $info['title']; ?>
                <a href="<?php //echo $this->createUrl('kb/create', array('id' => $info['id'])); ?>" style="font-size: 14px;margin-left: 15px;">编辑</a>
                </h3>
                <div class="row">
                </div>
                <div class="news-item-page">
                    <?php //echo $info['content']; ?>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-md-7">
        <div class="note note-success">
            <h4 class="block"><?php echo $info['title']; ?><span style="float: right;font-size: 13px;color: #bbb"><?php echo $info['created']; ?></span></h4>
            <p>
                <?php echo $info['content']; ?>
            </p>
        </div>
        <a class="btn blue" href="javascript:history.go(-1);" style="float: right;">返回</a>
    </div>
</div>