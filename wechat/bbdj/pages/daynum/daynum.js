// pages/daynum/daynum.js
const app = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    arrriqi:[],

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that=this
    //加载
    wx.showLoading({
      title: '加载中',
    })
    //请求接口
    wx.request({
      url: app.globalData.https +'index.php/index/zycount/count',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      method:'post',
      // 请求成功
      success(res) {
        console.log(res)
        wx.hideLoading()
        var data = res.data.data;

        // var msg = res.data.msg;
        that.setData({
          arrriqi: data,
        })
       
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