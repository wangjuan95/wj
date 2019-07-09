//app.js
App({
  onLaunch: function () {
    wx.loadFontFace({
      family: 'webfont',
      source: 'url("https://bbsh-com.oss-cn-beijing.aliyuncs.com/xiaochengxu/WeChat_img/images/PingFang Medium.ttf")',
      success: function (res) {
        // console.log(res.status) //  loaded
        // console.log('字体加载成功')
      },
      fail: function (res) {
        console.log(res.status) //  error
      }
    });
  },
  // https: 'http://192.168.1.116/Wxapplet/',
  // https: "http://www.81dja.com/Wxapplet/",
  globalData: {
    user_id: null,
    userInfo: null,
    // https: "https://www.81dja.com/Wxapplet/",
    https: 'http://cs.81bb.cn/Wxapplet/',
    imgUrl: 'https://bbsh-com.oss-cn-beijing.aliyuncs.com/',
  }
})