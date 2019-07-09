// pages/order/order.js
const app = getApp();
var time = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    num: 1,
    user_id: '',
    order: [],
  },
  order_item(e) {
    var id = e.currentTarget.dataset.id
    wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
      url: "/pages/wait/wait?id=" + id
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    console.log(options)
    var that = this;
    var user_id = wx.getStorageSync('user_id');
    that.setData({
      user_id: user_id
    })
    // 用户订单列表   
    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/getOrderList',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        user_id: that.data.user_id
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        // console.log(res.data.data)
        // console.log(res.data.data[0].time_appointment);
        var data = res.data.data
        // console.log(time.formatTimeTwo(res.data.data[0].time_appointment, 'M月D h:m'));
        for (var nums in data) {
          // console.log(data[nums].time_appointment);
          var time_appointment = data[nums].time_appointment;
          var time_appointment = time.formatTimeTwo(time_appointment, 'M月D h:m');
          data[nums].time_appointment = time_appointment;
        }
        // console.log(data)
        that.setData({
          order: data
        })
        that.down(options, true);
       
      },
      // 请求失败
      fail(res) {
        wx.showToast({
          title: '当前网络不稳定，请稍后再试',
          icon: 'none'
        })
        that.down(options);
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
     var that = this;
     var num = that.data.num;
     if(num > 1) {

     that.onLoad({d: false})
     }
     that.setData({
       num: num + 1
     })
    // console.log(options)
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
    wx.stopPullDownRefresh()
    this.onLoad({d: true })
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

  },
  down(opt, msg = false) {
    if (opt.d) {
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
      if (msg) {
        wx.showToast({
          title: '刷新成功',
          icon: "none"
        })
      }
    }
  }
})