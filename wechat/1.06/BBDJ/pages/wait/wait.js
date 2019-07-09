// pages/wait/wait.js
const app = getApp();
var time = require('../../utils/util.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    wait: [],
    order_id: '',
    user_id: '',
  },
  // 取消订单
  ad() {
    wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
      url: "/pages/cancel/cancel?id=" + this.data.order_id
    })
  },
  // 电话
  sf(e) {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.wait.mobile,
    })
  },
  calling(e) {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.wait.distributorinfo.contact_number,
    })
  },
  // 返回首页
  fh(e) {
    wx.switchTab({
      url: '/pages/index/index',
    })
  },
  // 再次支付
  zf(e) {
    var that = this;
    wx.request({
      // 地址
      url: app.globalData.https + 'AppletPay/make_order',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        user_id: that.data.user_id,
        orders_id: that.data.order_id
      },
      // 方式
      method: "post",
      // 请求成功
      success(resA) {
        console.log(resA);
        var nonceStr = resA.data.data.nonceStr;
        var packageA = resA.data.data.package;
        var paySign = resA.data.data.paySign;
        var signType = resA.data.data.signType;
        var timeStamp = resA.data.data.timeStamp;
        if (resA.data.code == 'success') {
          wx.requestPayment({
            timeStamp: timeStamp,
            nonceStr: nonceStr,   //字符串随机数
            package: packageA,
            signType: signType,
            paySign: paySign,
            success(res) {
              console.log(res.errMsg);    //requestPayment:ok==>调用支付成功
              wx.showToast({
                title: '支付成功',//这里打印出报名成功
                icon: 'success',
                duration: 1000,
                success(res1) {
                  setTimeout(function() {
                    that.onLoad({ id: that.data.order_id })
                    // wx.reLaunch({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
                    //   url: "/pages/wait/wait?id=" + order_id
                    // })
                  },1000)
                  // wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
                  //   url: "/pages/wait/wait?id=" + order_id
                  // })
                  
                }
              })

            },
            fail(res) {
              wx.showToast({
                title: '支付失败',//这里打印出报名成功
                icon: 'success',
                duration: 1000,
                success(res) {
                  setTimeout(function() {
                    wx.reLaunch({
                      url: '/pages/order/order',
                    })
                  },1000)
                }
              })
              // wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
              //   url: "/pages/wait/wait?id=" + order_id
              // })

            }
          })
        }

      },
      // 请求失败
      fail(res) {
        console.log("请求失败", res);
        // wx.showToast({
        //   title: '支付失败',//这里打印出报名成功
        //   icon: 'success',
        //   duration: 1000,
        //   success(res) {
        //     setTimeout(function () {
        //       wx.reLaunch({
        //         url: '/pages/order/order',
        //       })
        //     })
        //   }
        // })
      }
    })
  },
  ys(e) {
    var that = this;
    wx.showModal({
      content: '请确定师傅已经收拾干净屋内物品无遗失',
      success(res) {
        if (res.confirm) {
          wx.request({
            // 地址
            url: app.globalData.https + 'WxProgram/confirmCompleted',
            header: { 'content-type': 'application/x-www-form-urlencoded' },
            // 参数
            data: {
              orders_id: that.data.order_id,
              user_id: that.data.user_id
            },
            // 方式
            method: "post",
            // 请求成功
            success(res) {
              // console.log(res.data.data);
              that.onLoad({id: that.data.order_id})
            },
            // 请求失败
            fail(res) {
              console.log("请求失败", res);
            }
          })
        } else if (res.cancel) {
          console.log('用户点击取消')
        }
      }
    })
    
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // wx.reLaunch({
    //   url: '/pages/order/order',
    // })
    console.log(options)
    var that = this;
    var user_id = wx.getStorageSync('user_id');
    var order_id = options.id;
    console.log(order_id)
    that.setData({
      order_id: order_id,
      user_id: user_id
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
        console.log(data.time_appointment)
        var time_appointment = time.formatTimeTwo(data.time_appointment, 'M月D h:m');
        data.time_appointment = time_appointment;
        console.log(data.time_appointment)
        
        that.setData({
          wait: data
        })
        // 桶装水
        if (data.server_type == 1 && data.states == 1 && data.pay_states == 2 && data.is_cancel ==1) {
          wx.setNavigationBarTitle({
            title: '等待商家接单',
          })
        } else if (data.server_type == 1 && data.states == 2 && data.pay_states == 2 && data.is_cancel == 1) {
          wx.setNavigationBarTitle({
            title: '等待送货',
          })
        } else if (data.server_type == 1 && data.pay_states == 2 && data.is_cancel == 2) {
          wx.setNavigationBarTitle({
            title: '订单取消',
          })
        } else if (data.server_type == 1 && data.states == 4 && data.pay_states == 2 && data.is_cancel == 1) {
          wx.setNavigationBarTitle({
            title: '订单完成',
          })
        } else if (data.server_type == 1 && data.pay_states == 1 && data.is_cancel == 1) {
          wx.setNavigationBarTitle({
            title: '桶装水上门',
          })
        } 
        // 维修保洁
        else if (data.server_type == 2 ) {
          wx.setNavigationBarTitle({
            title: '维修保洁上门',
          })
        } 
        // 干洗
        else if (data.server_type == 4) {
          wx.setNavigationBarTitle({
            title: '干洗',
          })
        } 
        that.down(options, true);
      },
      
      // 请求失败
      fail(res) {
        // console.log("请求失败", res);
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
    this.onLoad({ id: this.data.order_id,d:true})
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
  down(opt,msg=false){
    if (opt.d){
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
      if(msg) {
        wx.showToast({
          title: '刷新成功',
          icon: "none"
        })
      }
    }
  }
})