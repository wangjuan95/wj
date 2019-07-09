// pages/expressSiteList/expressSiteList.js
const app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    expressSiteList: [],
    type_id: '',
  },
  // 新增地址
  submit(e) {
    wx.navigateTo({
      url: '/pages/expressSite/expressSite?id=' + this.data.type_id,
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var type_id = options.id;
    console.log(type_id)
    var user_id = wx.getStorageSync('user_id');
    that.setData({
      type_id: type_id,
      user_id: user_id
    })
    // 
    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/bookAddressList',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        user_id: user_id,
        type: type_id
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        var data = res.data.data;
        that.setData({
          expressSiteList: data
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