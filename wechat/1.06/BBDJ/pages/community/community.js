// pages/community/community.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    community: [],
    user_id: '',
  },
  btn(e) {
    var that = this;
    // console.log(e.currentTarget.dataset.id)
    var id = e.currentTarget.dataset.id;
    wx.showModal({
      content: '是否绑定驿站',
      success(res) {
        if (res.confirm) {
          wx.request({
            // 地址
            url: app.globalData.https + 'WxProgram/userAndBindStation',
            header: { 'content-type': 'application/x-www-form-urlencoded' },
            // 参数
            data: {
              user_id: that.data.user_id,
              distributor_id: id
            },
            // 方式
            method: "post",
            // 请求成功
            success(res) {
              // console.log(res.data.data);
              wx.showToast({
                title: '绑定驿站成功',
                success(res) {
                  setTimeout(function() {
                    wx.reLaunch({
                      url: '/pages/personal/personal',
                    })
                  }, 1000)
                }
              })
            },
            // 请求失败
            fail(res) {
              console.log("请求失败", res);
            }
          })
        } else if (res.cancel) {
          wx.showToast({
            title: '绑定驿站失败',
            icon: 'none'
          })
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var user_id = options.id;
    that.setData({
      user_id: user_id
    })
    wx.getLocation({
      type: 'gcj02',
      success(res) {
        var latitude = res.latitude
        var longitude = res.longitude
        wx.request({
          // 地址
          url: app.globalData.https + 'WxProgram/getNearbyStation',
          header: { 'content-type': 'application/x-www-form-urlencoded' },
          // 参数
          data: {
            user_id: that.data.user_id,
            longitude: longitude,
            latitude: latitude
          },
          // 方式
          method: "post",
          // 请求成功
          success(res) {
            that.setData({
              community: res.data.data

            })
          },
          // 请求失败
          fail(res) {
            console.log("请求失败", res);
          }
        })
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