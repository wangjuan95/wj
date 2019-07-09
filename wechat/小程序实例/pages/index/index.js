Page({

  /**
   * 页面的初始数据
   */
  data: {
    nav: ["标准收费","常见问题","评价","案例"],
    default: "标准收费",
  },
  btn(e) {
    var that = this;
    console.log(e.currentTarget.dataset.index);
    console.log(e.currentTarget.dataset.ct);
    that.setData({
      default: e.currentTarget.dataset.ct,
      toView: e.currentTarget.dataset.index
    })

  },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
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