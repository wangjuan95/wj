// pages/waittz/waittz.js
const app = getApp();
var time = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    wait: [],
    order_id: '',
  },
  ad(e) {
    wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
      url: "/pages/cancel/cancel?id=" + this.data.order_id
    })
  },
  xq(e) {
    var that = this;
    wx.reLaunch({
      url: '/pages/order/order'
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var user_id = wx.getStorageSync('user_id');
    var order_id = options.id;
    that.setData({
      order_id: order_id
    })

    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/getOrderInfo',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        user_id: user_id,
        order_id: order_id
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        var data = res.data.data
        // console.log(data.time_appointment)
        var time_appointment = time.formatTimeTwo(data.time_appointment, 'M月D h:m');
        data.time_appointment = time_appointment;
        that.setData({
          wait: data
        })

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