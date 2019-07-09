//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    date:'',
    timedata:'',
    time:'',
  
  },
 
  bindDateChange: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      date: e.detail.value
    })
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  //一个月的寄件量
  btn() {
    wx.navigateTo({
      url: '/pages/daynum/daynum'
    })
  },
  //近一个月圆通、中通总计件量
  zhexian(){
    wx.navigateTo({
      url: '/pages/ytztnum/ytztnum'
    })
  },
//一天的寄件量
 
  submit(){
    if (this.data.date == '') {
      wx.showToast({
        title: '请输入日期',
        icon: 'none',
        duration: 1000
      })
      return false
   
  

  }
    wx.navigateTo({
      url: '/pages/daynum/daynum?id=' + this.data.date
    })
  
  },
  //统计物品重量数量
  bindDateChange2: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      time: e.detail.value
    })
  },
  submit2() {
  
    wx.navigateTo({
      url: '/pages/weightnum/weightnum?id=' + this.data.time
    })

  },

  onLoad: function () {
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
    } else if (this.data.canIUse){
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          userInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.userInfo = res.userInfo
          this.setData({
            userInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },
  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  }
})
