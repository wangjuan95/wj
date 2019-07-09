// pages/makeWater/makeWater.js
const app = getApp();
var date = new Date();
var currentHours = date.getHours();
var currentMinute = date.getMinutes();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    station_id: '',
    user_id: '',
    makeWater: [],
    startDate: "选择上门时间",
    multiArray: [['今天', '明天'], [1, 2, 3, 4, 5, 6], [0, 10, 20]],
    multiIndex: [0, 0, 0],
    makeWatersite: [],
  },
  wx_pay(e) {
    var that = this
    // var user_id = wx.getStorageSync('user_id');
    // 时间
    var time_appointment = that.data.startDate
    var arr = that.data.makeWater;
    // 地址
    // var address_id = that.data.makeWatersite.id
    var commodity_id = [];
    var number = []
    for(var i in arr) {
      commodity_id.push(arr[i].id)
      number.push(arr[i].num)
    }
    // 商品种类
    var commodity_id = commodity_id.join(",");
    // 商品数量
    var number = number.join(",");
    if (this.data.makeWatersite == null) {
      wx.showToast({
        title: '请选择上门地址',
        icon: 'none',
        duration: 1000
      })
      return false
      // console.log(1)
    } else if (this.data.startDate == '选择上门时间') {
      wx.showToast({
        title: '请选择上门时间',
        icon: 'none',
        duration: 1000
      })
      return false
    } else {
      var address_id = that.data.makeWatersite.id
      wx.request({
        // 地址
        url: app.globalData.https + 'WxProgram/BuyCargoOrderGoods',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        // 参数
        data: {
          station_id: that.data.station_id,
          commodity_id: commodity_id,
          user_id: that.data.user_id,
          number: number,
          address_id: address_id,
          server_type: 1,
          time_appointment: time_appointment
        },
        // 方式
        method: "post",
        // 请求成功
        success(res) {
          // console.log(res.data.data.order_id);
          var order_id = res.data.data.order_id;
          // 请求支付数据
          wx.request({
            // 地址
            url: app.globalData.https + 'AppletPay/make_order',
            header: { 'content-type': 'application/x-www-form-urlencoded' },
            // 参数
            data: {
              user_id: that.data.user_id,
              orders_id: order_id
            },
            // 方式
            method: "post",
            // 请求成功
            success(resA) {
              console.log(resA.data.data);
              var nonceStr = resA.data.data.nonceStr;
              var packageA = resA.data.data.package;
              var paySign = resA.data.data.paySign;
              var signType = resA.data.data.signType;
              var timeStamp = resA.data.data.timeStamp;
              console.log(resA.data.code)
              // console.log(resA.data.msg)
              if (resA.data.code == 'success') {
                wx.requestPayment({
                  timeStamp: timeStamp,
                  nonceStr: nonceStr,   //字符串随机数
                  package:packageA,
                  signType: signType,
                  paySign: paySign,
                  success (res) {
                    console.log(res.errMsg);    //requestPayment:ok==>调用支付成功
                    wx.showToast({
                      title: '支付成功',//这里打印出报名成功
                      icon: 'success',
                      duration: 1000,
                      success(res1) {
                        setTimeout(function() {
                          wx.reLaunch({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
                            url: "/pages/wait/wait?id=" + order_id
                          })
                        },1000)
                      }
                    })
                    
                  },
                  fail (res) {
                    wx.showToast({
                      title: '支付失败',//这里打印出报名成功
                      icon: 'success',
                      duration: 1000,
                      success(resA) {
                        setTimeout(function() {
                          wx.reLaunch({
                            url: '/pages/order/order',
                          })
                        },1000)
                        
                      }
                    })
                  }
                })
              }
              
            },
            // 请求失败
            fail(res) {
              console.log("请求失败", res);
            }
          })
          
          
        },
        // 请求失败
        fail(res) {
          console.log("请求失败", res);
        }
      })
    }

    
  },
  
  // bindDateChange(e) {
  //   // console.log('picker发送选择改变，携带值为', e.detail.value)
  //   this.setData({
  //     startDate: e.detail.value
  //   })
  // },

  hq() {
    wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
      url: "/pages/tzsite/tzsite"
    })
  },

  
 
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    var station_id = options.id;
    var user_id = wx.getStorageSync('user_id');
    var user_info = wx.getStorageSync("key")
    that.setData({
      user_id: user_id,
      station_id: station_id,
      makeWater: user_info
    })
    // 获取数据

    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/getUserOrderBook',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        user_id: that.data.user_id,
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        // console.log(res.data.data);
        var data = res.data.data;
        that.setData({
          makeWatersite: data
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

  },

  pickerTap: function () {
    date = new Date();

    var monthDay = ['今天', '明天'];
    var hours = [];
    var minute = [];

    currentHours = date.getHours();
    currentMinute = date.getMinutes();

    // 月-日
    for (var i = 2; i <= 2; i++) {
      var date1 = new Date(date);
      date1.setDate(date.getDate() + i);
      var md = (date1.getMonth() + 1) + "-" + date1.getDate();
      monthDay.push(md);
    }

    var data = {
      multiArray: this.data.multiArray,
      multiIndex: this.data.multiIndex
    };

    if (data.multiIndex[0] === 0) {
      if (data.multiIndex[1] === 0) {
        this.loadData(hours, minute);
      } else {
        this.loadMinute(hours, minute);
      }
    } else {
      this.loadHoursMinute(hours, minute);
    }

    data.multiArray[0] = monthDay;
    data.multiArray[1] = hours;
    data.multiArray[2] = minute;

    this.setData(data);
  },

  bindMultiPickerColumnChange: function (e) {
    date = new Date();

    var that = this;

    var monthDay = ['今天', '明天'];
    var hours = [];
    var minute = [];

    currentHours = date.getHours();
    currentMinute = date.getMinutes();

    var data = {
      multiArray: this.data.multiArray,
      multiIndex: this.data.multiIndex
    };
    // 把选择的对应值赋值给 multiIndex
    data.multiIndex[e.detail.column] = e.detail.value;

    // 然后再判断当前改变的是哪一列,如果是第1列改变
    if (e.detail.column === 0) {
      // 如果第一列滚动到第一行
      if (e.detail.value === 0) {

        that.loadData(hours, minute);

      } else {
        that.loadHoursMinute(hours, minute);
      }

      data.multiIndex[1] = 0;
      data.multiIndex[2] = 0;

      // 如果是第2列改变
    } else if (e.detail.column === 1) {

      // 如果第一列为今天
      if (data.multiIndex[0] === 0) {
        if (e.detail.value === 0) {
          that.loadData(hours, minute);
        } else {
          that.loadMinute(hours, minute);
        }
        // 第一列不为今天
      } else {
        that.loadHoursMinute(hours, minute);
      }
      data.multiIndex[2] = 0;

      // 如果是第3列改变
    } else {
      // 如果第一列为'今天'
      if (data.multiIndex[0] === 0) {

        // 如果第一列为 '今天'并且第二列为当前时间
        if (data.multiIndex[1] === 0) {
          that.loadData(hours, minute);
        } else {
          that.loadMinute(hours, minute);
        }
      } else {
        that.loadHoursMinute(hours, minute);
      }
    }
    data.multiArray[1] = hours;
    data.multiArray[2] = minute;
    this.setData(data);
  },

  loadData: function (hours, minute) {

    var minuteIndex;
    if (currentMinute > 0 && currentMinute <= 30) {
      minuteIndex = 30;
      // minuteIndex = minuteIndex.toFixed(2)
    } else {
      minuteIndex = 60;
    }

    if (minuteIndex == 60) {
      // 时
      for (var i = currentHours + 1; i < 24; i++) {
        hours.push(i);
      }
      // 分
      for (var i = 0; i < 60; i += 30) {
        for (var i = 0; i < 60; i += 30) {
          if (i == 0) {
            // i = "00"
            minute.push("00");
          } else {
            minute.push(i);
          }
        }
      }
    } else {
      // 时
      for (var i = currentHours; i < 21; i++) {
        hours.push(i);
      }
      // 分
      for (var i = minuteIndex; i < 60; i += 30) {
        for (var i = 0; i < 60; i += 30) {
          if (i == 0) {
            // minute.push("00");
          } else {
            minute.push(i);
          }
        }
      }
    }
  },

  loadHoursMinute: function (hours, minute) {
    // 时
    for (var i = 8; i < 21; i++) {
      hours.push(i);
    }
    // 分
    for (var i = 0; i < 60; i += 30) {
      for (var i = 0; i < 60; i += 30) {
        if (i == 0) {
          minute.push("00");
        } else {
          minute.push(i);
        }
      }
    }
  },

  loadMinute: function (hours, minute) {
    var minuteIndex;
    if (currentMinute > 0 && currentMinute <= 30) {
      minuteIndex = 30;
    } else {
      minuteIndex = 60;
    }

    if (minuteIndex == 60) {
      // 时
      for (var i = currentHours + 1; i < 21; i++) {
        hours.push(i);
      }
    } else {
      // 时
      for (var i = currentHours; i < 21; i++) {
        hours.push(i);
      }
    }
    // 分
    for (var i = 0; i < 60; i += 30) {
      if (i == 0) {
        minute.push("00");
      } else {
        minute.push(i);

      }
    }
  },

  bindStartMultiPickerChange: function (e) {
    var that = this;
    var monthDay = that.data.multiArray[0][e.detail.value[0]];
    var hours = that.data.multiArray[1][e.detail.value[1]];
    var minute = that.data.multiArray[2][e.detail.value[2]];

    if (monthDay === "今天") {
      var month = date.getMonth() + 1;
      var day = date.getDate();
      monthDay = month + "月" + day + "日";
    } else if (monthDay === "明天") {
      var date1 = new Date(date);
      date1.setDate(date.getDate() + 1);
      monthDay = (date1.getMonth() + 1) + "月" + date1.getDate() + "日";

    } else {
      var month = monthDay.split("-")[0]; // 返回月
      var day = monthDay.split("-")[1]; // 返回日
      monthDay = month + "月" + day + "日";
    }

    var startDate = monthDay + " " + hours + ":" + minute;
    that.setData({
      startDate: startDate
    })
  },
})