// pages/noorder/noorder.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    noorder:[],
    page:1,
    last_page:'',
    title:'',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
   
    
   this.reRresh();


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
    console.log('下拉刷新')
    // wx.showNavigationBarLoading() //在标题栏中显示加载

    // //模拟加载
    // setTimeout(function () {
    //   // complete
    //   wx.hideNavigationBarLoading() //完成停止加载
    //   wx.stopPullDownRefresh() //停止下拉刷新
    // }, 1500);
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    console.log(this.__data__)
      if(this.__data__.last_page>this.__data__.page){
          this.setData({
            page: this.data.page+1,
          })
        this.reRresh();
      }else{
        this.setData({
          title: '加载完毕',
        })
      }
    // this.data.noorder = this.data.noorder.concat(this.data.noorder)
    
      console.log(this.data.page)
  },

  /**
   * 用户console.log(this)点击右上角分享
   */
  onShareAppMessage: function () {

  },

  reRresh: function (){
    var that = this
    wx.showLoading({
      title: '加载中',
    })

    wx.request({
      url: app.globalData.https + 'index.php/index/noorderdistributor/noOrder',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 方式
      method: "post",
      data:{
        page:this.data.page,
      },
      // 请求成功
      success(res) {
        
        wx.hideLoading()

        var data = res.data.data;

        that.setData({
          noorder: that.data.noorder.concat(data),
          title:'下拉加载',
          // page:page+1,
          last_page: res.data.page.last_page,


        })
      },
      // 请求失败
      fail(res) {

        wx.showToast({
          title: '网不好稍后重试',
          icon: 'none',
          duration: 2000
        })
        console.log("请求失败", res);
      }



    })


  }
})