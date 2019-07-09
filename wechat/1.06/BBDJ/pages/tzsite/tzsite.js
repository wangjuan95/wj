// pages/tzsite/tzsite.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    user_id: '',
    condition: true,
    tzsite:[],
    id: '',
  },
  fh(e) {
    // wx.setStorageSync('xxdz', e.currentTarget.dataset.id);
    var pages = getCurrentPages();
    var prevPage = pages[pages.length - 2]; //上一个编辑款项页面
    //直接调用上一个页面的setData()方法，把数据存到上一个页面即编辑款项页面中去  
    // console.log(currentfriend.name)
    // console.log(prevPage)
    // console.log(e.currentTarget.dataset.id)
    prevPage.setData({
      makeWatersite: e.currentTarget.dataset.id,
    });
    wx.navigateBack({
      //返回上一级
      delta: 1,
    })

  },
  // 跳转新增地址
  submit() {
    wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
      url: "/pages/site/site"
    })
  },
  // 编辑
  edit(e) {
    // console.log(e.target.dataset.id)
    
    wx.navigateTo({    //保留当前页面，跳转到应用内的某个页面（最多打开5个页面，之后按钮就没有响应的）
      url: "/pages/bjsite/bjsite?id=" + e.target.dataset.id
    })
  },
  // 删除
  del(e) {
    var that = this
    wx.showModal({
      title: '提示',
      content: '确定要删除吗',
      success(res) {
        if (res.confirm) {
          // console.log('用户点击确定')
          wx.request({
            // 地址
            url: app.globalData.https + 'WxProgram/delectUserAddress',
            header: { 'content-type': 'application/x-www-form-urlencoded' },
            // 参数
            data: {
              user_id: that.data.user_id,
              address_id: e.target.dataset.id
            },
            // 方式
            method: "post",
            // 请求成功
            success(res) {
              // console.log(res.data.data);
              wx.request({
                // 地址
                url: app.globalData.https + 'WxProgram/getUserBook',
                header: { 'content-type': 'application/x-www-form-urlencoded' },
                // 参数
                data: {
                  user_id: that.data.user_id
                },
                // 方式
                method: "post",
                // 请求成功
                success(res) {
                  var data = res.data.data;
                  that.setData({
                    tzsite: data
                  });
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


        } else if (res.cancel) {
          // console.log('用户点击取消')
        }
      }
    })
  },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var user_id = wx.getStorageSync('user_id');
    that.setData({
      user_id: user_id
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
    // console.log(1)
    var that = this
    wx.request({
      // 地址
      url: app.globalData.https + 'WxProgram/getUserBook',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {
        user_id: that.data.user_id
      },
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        // console.log(res.data.data);
        var data = res.data.data;
        that.setData({
          tzsite: data
        });
      },
      // 请求失败
      fail(res) {
        console.log("请求失败", res);
      }
    })
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