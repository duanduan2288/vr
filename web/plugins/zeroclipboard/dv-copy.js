var clip = new ZeroClipboard($(".copytext"),{
    moviePath: "/assets/plugins/zeroclipboard/ZeroClipboard.swf",
  trustedDomains: ['*'],
  allowScriptAccess: "always"
});
clip.addEventListener('complete', function (client){
    alert("复制成功");
});