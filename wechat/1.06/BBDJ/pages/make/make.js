// pages/make/make.js
const app = getApp();
var date = new Date();
var currentHours = date.getHours();
var currentMinute = date.getMinutes();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    stage: '',
    user_id: '',
    id: "",
    makeWatersite: [],
    startDate: "选择上门时间",
    multiArray: [['今天', '明天'], [1, 2, 3, 4, 5, 6], [0, 10, 20]],
    multiIndex: [0, 0, 0],
  },
  ac(e) {
    
    var that = this
    // 时间
    var time_appointment = that.data.startDate;
    // var arr = that.data.makeWater;
    // 地址
    // var address_id = that.data.makeWatersite.id;
    // console.log(that.data.makeWatersite)
    // 商品种类
    var commodity_id = that.data.id
    if (that.data.makeWatersite == null) {
      wx.showToast({
        title: '请选择上门地址',
        icon: 'none',
        duration: 1000
      })
      return false
      
      // console.log(1)
    } else if (that.data.startDate == "选择上门时间") {
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
        url: app.globalData.https + 'WxProgram/BuyCargoOrderServer',
        header: { 'content-type': 'application/x-www-form-urlencoded' },
        // 参数
        data: {
          commodity_id: commodity_id,
          user_id: that.data.user_id,
          address_id: address_id,
          server_type: 2,
          time_appointment: time_appointment
        },
        // 方式
        method: "post",
        // 请求成功
        success(res) {
          // console.log(res.data.data.order_id)
          wx.reLaunch({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
            url: "/pages/waittz/waittz?id=" + res.data.data.order_id
          })
        },
        // 请求失败
        fail(res) {
          console.log("请求失败", res);
        }
      })
    }

    
  },
  hq() {
    wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
      url: "/pages/tzsite/tzsite"
    })
  },
  bindDateChange(e) {
    // console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      time: e.detail.value
    })
  },
// 电话
  calling(e) {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.stage.number,
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var user_id = wx.getStorageSync('user_id');
    var stage = wx.getStorageSync('stage');
    // console.log(stage)
    // 获取上一个页面参数ID
    var id = options.id
    // console.log(id)
    that.setData({
      id: id,
      user_id: user_id,
      stage: stage
    })

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
    var that = this
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
      for (var i = currentHours; i < 24; i++) {
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
    for (var i = 8; i < 24; i++) {
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
      for (var i = currentHours + 1; i < 24; i++) {
        hours.push(i);
      }
    } else {
      // 时
      for (var i = currentHours; i < 24; i++) {
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