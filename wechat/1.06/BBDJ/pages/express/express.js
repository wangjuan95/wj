// pages/express/express.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    express: [],
    array: ["请选择物品类型","文件", "数码产品","生活用品","服饰","食品","其他"],
    weight: ["请选择物品重量", "1KG", "2KG", "3KG", "4KG", "5KG", "其他"],
    array_index: 0,
    weight_index: 0,
    site_jj: [],
    site_sj: [],
  },
  bindtype: function (e) {
    var that = this
    console.log(e.detail.value)
    that.setData({
      array_index: e.detail.value
    });
  },
  bindweight: function (e) {
    var that = this
    console.log(e.detail.value)
    that.setData({
      weight_index: e.detail.value
    });
  },
  // 下一步
  next(e) {
    var that = this;
    if (that.data.site_jj == null) {
      wx.showToast({
        title: '请填写寄件人信息',
        icon: 'none',
        duration: 1000
      })
      return false
    } else if (that.data.site_sj == null) {
      wx.showToast({
        title: '请填写收件人信息',
        icon: 'none',
        duration: 1000
      })
      return false
    } else if (that.data.array[that.data.array_index] == "请选择物品类型") {
      wx.showToast({
        title: '请选择物品类型',
        icon: 'none',
        duration: 1000
      })
      return false
    } else if (that.data.weight[that.data.weight_index] == "请选择物品重量") {
      wx.showToast({
        title: '请选择物品重量',
        icon: 'none',
        duration: 1000
      })
      return false
    } else {
      wx.reLaunch({
        url: '/pages/expressNext/expressNext',
      })
    }



    
  },
  // 认证
  authentication(e) {
    console.log(1)
  },
  // 填写地址
  siteBtn(e) {
    // console.log(e.currentTarget.dataset.id)
    wx.navigateTo({
      url: '/pages/expressSite/expressSite?id=' + e.currentTarget.dataset.id,
    })
  },
  // 选择地址
  siteSelect(e) {
    // console.log(e.currentTarget.dataset.id)
    wx.navigateTo({
      url: '/pages/expressSiteList/expressSiteList?id=' + e.currentTarget.dataset.id,
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this; 
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