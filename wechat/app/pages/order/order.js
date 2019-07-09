// pages/order/order.js
const app = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    date:'',
    otherorder:'',
    value:'',
  },
  //单选按钮
  radioChange: function (e) {
    this.setData({
      value: e.detail.value
    })
    
  },
  searchbtn(){

    if (this.data.value == '') {
      wx.showToast({
        title: '请选择按钮',
        icon: 'none',
        duration: 1000
      })
      return false
    }
    wx.navigateTo({
      url: '/pages/searchorder/searchorder?type=' + this.data.value
    })

  },

  bindDateChange: function (e) {
    this.setData({
      date: e.detail.value
    })
  },

//无订单页面
  noorder(){
    wx.navigateTo({
      url: '/pages/noorder/noorder'
    })
  },
  submit(e){
    
    if (this.data.date == '') {
      wx.showToast({
        title: '请输入日期',
        icon: 'none',
        duration: 1000
      })
      return false
    } else {
      wx.showLoading({
        title: '加载中',
      })
     
      
   
       var that=this
      var date=this.data.date
    
      wx.request({
        url: app.globalData.https + 'index.php/index/contact/dayOtherorder',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        // 方式
        method: "post",
        //参数
        data: {
          day: date
        },
        // 请求成功
        success(res) {
          wx.hideLoading()
          var shuju = res.data.data;
          console.log(shuju)
          that.setData({
            otherorder: shuju,

          })
        },
        // 请求失败
        fail(res) {
          console.log("请求失败", res);
        }

      })
    }

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