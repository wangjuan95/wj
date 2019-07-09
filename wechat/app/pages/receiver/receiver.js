// pages/receiver/receiver.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
   
    dis_id:'',
    addr:'',
    arrlist:'',
    
  },
  bindPickerChange: function (e) {
    var that = this;
    var station_id = that.data.arrlist[e.detail.value].id
    // console.log(station_id)
    that.setData({ //给变量赋值
      //根据索引去查对应的id
      index: e.detail.value, //每次选择了下拉列表的内容同时修改下标然后修改显示的内容,显示的内容和选择的内容一致
      dis_id: station_id
     
    })
  },

//取件员
  onbtn(e){
    this.setData({
      dis_id: e.detail.value
    })
  },
  submit(){
    if (this.data.dis_id == '') {
      wx.showToast({
        title: '请输入id',
        icon: 'none',
        duration: 1000
      })
      return false
    }
    wx.navigateTo({
      url: '/pages/receiverlist/receiverlist?id=' + this.data.dis_id
    })


  },
//派件员
  addrbtn(e) {
    this.setData({
      addr: e.detail.value
    })
  },
  addrsubmit() {
    if (this.data.addr == '') {
      wx.showToast({
        title: '请输入地址',
        icon: 'none',
        duration: 1000
      })
      return false
    }
    console.log(this.data.addr)
    wx.navigateTo({
      url: '/pages/courlist/courlist?id=' + this.data.addr
    })


  },
  all(){
    wx.navigateTo({
      url: '/pages/allreceiverlist/allreceiverlist'
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that=this
    wx.request({
      url: app.globalData.https +'index.php/index/contact/yizhan',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 方式
      method: "post",
     
      // 请求成功
      success(res) {
        var data = res.data.data;
        console.log(data)
       
        that.setData({
          arrlist: data,
        


        })
      },
      // 请求失败
      // fail(res3) {
      //   console.log("请求失败", res3);
      //   wx.showToast({
      //     title: '网不好稍后重试',
      //     icon: 'none',
      //     duration: 2000
      //   })
      // }



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