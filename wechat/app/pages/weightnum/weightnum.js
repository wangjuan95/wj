// pages/weightnum/weightnum.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    number:'',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that=this
    console.log(options.id)

      var id = options.id
      wx.showLoading({
        title: '加载中',
      })

      //调接口
      wx.request({
        url: app.globalData.https + 'index.php/index/weight/weightCount',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        // 方式
        method: "post",
        //参数
        data: {
          time: id
        },
        // 请求成功
        success(res) {
          wx.hideLoading()
          var shuju = res.data.data;
          console.log(shuju)
          that.setData({
            number: shuju,

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