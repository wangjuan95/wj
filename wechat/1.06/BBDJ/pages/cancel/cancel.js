// pages/cancel/cancel.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    order_id: '',
    user_id: '',
    cancel:'',
    value: '',
  },
  ad(e) {
    wx.navigateBack({
      //返回上一级
      delta: 1,
    })
  },
  qx(e) {
    var that = this;
    // console.log(that.data.value)
    if (that.data.value == undefined || that.data.value == "") {
      wx.showToast({
        title: '请选择取消原因',
        icon: 'none'
      })
      // return false
    } else {
      wx.showModal({
        content: '是否取消订单',
        success(res) {
          if (res.confirm) {
            wx.request({
              // 地址
              url: app.globalData.https + 'WxProgram/CancellationOrder',
              header: { 'content-type': 'application/x-www-form-urlencoded' },
              // 参数
              data: {
                user_id: that.data.user_id,
                order_id: that.data.order_id,
                reason_id: that.data.value
              },
              // 方式
              method: "post",
              // 请求成功
              success(res) {
                console.log(res.data.code);
                if (res.data.code = "success") {
                  wx.showToast({
                    title: '取消订单成功',
                    icon: 'none',
                    success(res) {
                      setTimeout(function() {
                        wx.reLaunch({
                          url: '/pages/order/order',
                        })
                      },1000)
                     
                    }
                  })
                }
                // var data = res.data.data;
                // that.setData({
                //   index_containerList: data
                // });
              },
              // 请求失败
              fail(res) {
                console.log("请求失败", res);
              }
            })
          } else if (res.cancel) {
          }
        }
      })
    }
  },
  checkboxChange(e){
    var value = e.detail.value;
    this.setData({
      value: value
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var user_id = wx.getStorageSync('user_id');
    var order_id = options.id
    that.setData({
      user_id: user_id,
      order_id: order_id
    })
    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/getCancelList',
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
        console.log(data)
        that.setData({
          cancel: data
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