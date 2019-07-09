const app = getApp();

// pages/daynum/daynum.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    arrTime:[],
    arrriqi:[],
   id:'',
   msg:'',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  
  onLoad: function (options) {
    var id = options.id
    var that = this
    wx.showLoading({
      title: '加载中',
    })
   
    if(id){
     
      wx.request({
        url: app.globalData.https + 'index.php/index/statistictypenum/dayTypenum',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        // 方式
        method: "post",
        //参数
        data:{
          day:id
        },
        // 请求成功
        success(res) {
          
            wx.hideLoading()
         
          console.log(res)
          var data = res.data.data;
      
          var msg = res.data.msg;
          that.setData({
            arrriqi: data,
            id:id,
            msg:msg

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
    }else{
      wx.request({
        url: app.globalData.https + 'index.php/index/statistictypenum/monthnum',

        // 方式
        method: "post",
        // 请求成功
        success(res) {
         
            wx.hideLoading()
         
          var data = res.data.data;

          that.setData({
            arrTime: data
          })
        },



      })
    }
   
    
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