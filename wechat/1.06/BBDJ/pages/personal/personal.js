// pages/personal/personal.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    user_id: "",
    userInfo: [],
    purePhoneNumber: '',
  },
  // 地址
  btnsite(e) {
    wx.navigateTo({
      url: '/pages/tzsite/tzsite',
    })
  },
  // 驿站 
  btnyz(e) {
    wx.navigateTo({
      url: '/pages/community/community?id=' + this.data.user_id
    })
  },
  // 联系客服
  btnphone(e) {
    wx.makePhoneCall({
      phoneNumber: '400-775-0008' // 仅为示例，并非真实的电话号码
    })
  },
  phonenumber(e) {
    var that = this;
    var iv = e.detail.iv;
    var encryptdData = encodeURIComponent(e.detail.encryptedData);
    if (e.detail.errMsg == "getPhoneNumber:ok") {
      wx.login({
        success(res) {
          if (res.code) {
            var code = res.code;
            wx.request({
              // 地址
              url: app.globalData.https + 'WxProgram/bindPhoneNumber',
              header: { 'content-type': 'application/x-www-form-urlencoded' },
              // 参数
              data: {
                encryptedData: encryptdData,
                iv: iv,
                user_id: that.data.user_id,
                code: code
              },
              // 方式
              method: "post",
              // 请求成功
              success(res) {
                console.log(res.data.data)
                var purePhoneNumber = res.data.data.purePhoneNumber;
                console.log(purePhoneNumber)
                that.setData({
                  purePhoneNumber: purePhoneNumber
                })
                // console.log(1)
              },
              // 请求失败
              fail(res) {
                console.log("请求失败", res);
              }
            })
          }
        }
      })
    } else {
      wx.showToast({
        title: '授权失败',
        icon: 'none'
      })
    }
    
    
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var user_id = wx.getStorageSync('user_id');
    that.setData({
      user_id: user_id
    })
    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/getUserinfo',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        user_id: user_id
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        var data = res.data.data;
        var purePhoneNumber = data.account
        that.setData({
          userInfo: data,
          purePhoneNumber: purePhoneNumber
        });
      },
      // 请求失败
      fail(res) {
        console.log("请求失败", res);
      }
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})