const app = getApp();
Page({
  data: {
    //判断小程序的API，回调，参数，组件等是否在当前版本可用。
    canIUse: wx.canIUse('button.open-type.getUserInfo'),

  },
  onLoad: function () {
    wx.showLoading({
      title: '加载中',
      mask: true
    })




    var that = this;
    // 查看是否授权
    wx.getSetting({
      success: function (res) {
        if (res.authSetting['scope.userInfo']) {
          that.login();

        }else{
          wx.hideLoading()
          //用户没有授权
          console.log("用户没有授权");
        }
      }
    
    })
    
  },

  bindGetUserInfo: function (e) {
    if (e.detail.userInfo) {
      //用户按了允许授权按钮
      var that = this;
      that.login();

    } else {
      wx.hideLoading()
      //用户按了拒绝按钮
      wx.showModal({
        title: '警告',
        content: '您点击了拒绝授权，将无法进入小程序，请授权之后再进入!!!',
        showCancel: false,
        confirmText: '返回授权',
        success: function (res) {
          if (res.confirm) {
            console.log('用户点击了“返回授权”')
          }
        }
      })
    } 
   

  },


  login(){
    wx.login({
      success: function (ress) {
      
        var code = ress.code;
        console.log(code)
        wx.getUserInfo({
          success(res2){
           // console.log(res2)
            // wx.setStorageSync('user_img', res2.userInfo.avatarUrl)
            var encryptedData = res2.encryptedData;
            var iv = res2.iv;
            //console.log(encryptedData)
           // console.log(iv)
            wx.request({
              url: app.globalData.https + 'index.php/index/login/wxsq',
              header: { 'content-type': 'application/x-www-form-urlencoded' },
              data: {
                encryptedData:encryptedData,
                iv:iv,
                code: code,
              },
              method: "post",
              header: { 'content-type': 'application/x-www-form-urlencoded' },
              success: function (res) {
                      console.log(res)
                    if (res.data.code=='success'){
                        // wx.setStorageSync('unionId', res3.data.data.unionId)
                        wx.setStorageSync('openId', res.data.data.openId)
                        wx.switchTab({
                          url: '/pages/index/index'
                        })
                      }
               

              },
              // 请求失败
              fail(res3) {
                console.log("请求失败", res3);
                wx.showToast({
                  title: '网不好稍后重试',
                  icon: 'none',
                  duration: 2000
                })
              }
            });


          }
        })


        // wx.switchTab({
        //   url: '/pages/index/index'
        // })
      }

    });
  },



    //获取用户信息接口
  // queryUsreInfo: function () {
  //   wx.request({
  //     url:'',
  //     data: {
  //       openid: app.globalData.openid
  //     },
  //     header: {
  //       'content-type': 'application/json'
  //     },
  //     success: function (res) {

  //       getApp().globalData.userInfo = res.data;
  //     }
  //   }) 
  // },

})
