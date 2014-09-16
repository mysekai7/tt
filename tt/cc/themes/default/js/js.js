function trim(str){ //删除左右两端的空格
　　return str.replace(/(^\s*)|(\s*$)/g, "");
}
function ltrim(str){ //删除左边的空格
　　return str.replace(/(^\s*)/g,"");
}
function rtrim(str){ //删除右边的空格
　　return str.replace(/(\s*$)/g,"");
}