// pages/index/index.js
const QQMapWX = require('../../style/qqmap-wx-jssdk.js');
const wxMap = new QQMapWX({
  key: 'OJ5BZ-H6ZC4-23GUR-XMSSJ-K4JX5-SBFKZ' // 必填
});
const app = getApp();
Page({
  data: {
    isShow: true,
    stage: [],
    //用户个人信息
    userInfo: {
      avatarUrl: "",//用户头像
      nickName: "",//用户昵称
    },
    user_id: '',
    show: false,
    isHide: true,
    index_site: "",
    imgUrl: app.globalData.imgUrl,
    index_containerList: [],
  },
  qqq(e) {
    wx.showModal({
      title: '提示',
      content: '寄快递服务暂未开放',
    })
  },
  // 一键报修
  calling(e) {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.stage.number,
    })
  },
  
  // 授权手机号
  getPhoneNumber(e) {
    var that = this
    var encryptedData = encodeURIComponent(e.detail.encryptedData);
    var iv = e.detail.iv
    if (e.detail.errMsg == "getPhoneNumber:ok") {
      that.setData({
        show: false
      })
      wx.login({
        success(res) {
          if (res.code) {
            var code = res.code;
            wx.request({
              // 地址
              url: app.globalData.https + 'WxProgram/bindPhoneNumber',
              header: { 'content-type': 'application/x-www-form-urlencoded' },
              // 参数
              data: {
                code: code,
                encryptedData: encryptedData,
                iv: iv,
                user_id: that.data.user_id
              },
              // 方式
              method: "post",
              // 请求成功
              success(res3) {
                // console.log(res3.data.data.purePhoneNumber)
                app.globalData.userPhoneNumber = res3.data.data.purePhoneNumber;
              },
              // 请求失败
              fail(res3) {
                console.log("请求失败", res);
              }
            })
          }
        }
      })
      // 获取地理位置
      that.getLocation()
    } else {
      that.setData({
        show: false
      })
      // 获取地理位置
      that.getLocation()
    }
    
  },
  //  授权登录
  bindGetUserInfo(e) {
    var that = this 
    if (e.detail.userInfo) {
      // console.log("用户点击了允许");
      that.setData({
        isHide: true,
        show: true
      })
      // 获取地理位置
      that.getLocation()
    } else {
      // console.log("用户点击了拒绝");
      wx.showModal({
        title: '警告',
        content: '您点击了拒绝授权,将无法正常显示个人信息,请重新获取授权。',
      })
    }
    wx.login({
      success(res) {
        if (res.code) {
          var code = res.code;
          wx.getUserInfo({
            success(res2) {
              var encryptedData = encodeURIComponent(res2.encryptedData);
              var iv = res2.iv;
              wx.request({
                // 地址
                url: app.globalData.https + 'WxProgram/wxlogin',
                header: { 'content-type': 'application/x-www-form-urlencoded' },
                // 参数
                data: {
                  code:code,
                  encryptedData: encryptedData,
                  iv: iv
                },
                // 方式
                method: "post",
                // 请求成功
                success(res3) {
                  // console.log(res3)
                  // app.globalData.userInfo = res3.data.data.userInfo
                  var user_id = res3.data.data.user_id
                  wx.setStorageSync('user_id', user_id)
                  that.setData({
                    user_id: user_id
                  })
                },
                // 请求失败
                fail(res3) {
                  console.log("请求失败", res);
                }
              })
            }
          })
          
        }
      }
    })
  },
  // 获取位置方法
  getLocation() {
    var that = this
    wx.getLocation({
      type: 'gcj02',
      success: function (res) {
        // console.log(res)
        var latitude = res.latitude
        var longitude = res.longitude
        var speed = res.speed
        var accuracy = res.accuracy
        wx.request({
          // 地址
          url: app.globalData.https + 'WxProgram/userBindStation',
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
            // console.log(res.data.data)
            if (res.data.code == "success") {
              wx.showTabBar({

              })
              wx.setStorageSync('stage', res.data.data);
              var username = res.data.data.username;
              if (username.length > 8) {
                var username = username.substring(0, 8) + "...";
              } else {
                var username = username;
              }
              res.data.data.username = username;
              that.setData({
                stage: res.data.data
              })

              wxMap.reverseGeocoder({
                location: {
                  latitude: latitude,
                  longitude: longitude
                },
                success: function (res) {
                  // console.log(res);
                  that.setData({
                    index_site: res.result.address_component.street_number
                  })
                },

              })
            } else {
              that.setData({
                isShow: false
              })
            }
          },
          // 请求失败
          fail(res) {
            console.log("请求失败", res);
            // this.setData({
            //   isShow: false
            // })
          }
        })
        // console.log(res);
        // wxMap.reverseGeocoder({
        //   location: {
        //     latitude: latitude,
        //     longitude: longitude
        //   },
        //   success: function (res) {
        //     console.log(res);
        //     that.setData({
        //       index_site: res.result.address_component.street_number
        //     })
        //   },

        // })
      },
      fail(res) {
        // console.log(res)
        that.onLoad()
      },
    })
  },
  /**
     * 生命周期函数--监听页面加载
     */
  onLoad: function (options) {
    var that = this;
    var user_id = wx.getStorageSync('user_id')
    that.setData({
      user_id: user_id
    })
    wx.request({
      // 地址
      url: app.globalData.https +'WxProgram/IndexType',
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      // 参数
      data: {},
      // 方式
      method: "post",
      // 请求成功
      success(res) {
        var data = res.data.data;
        that.setData({
          index_containerList: data
        });
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
    });
    wx.getSetting({
      success: function (res) {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function (res) {
              that.setData({
                isHide: true
              })
              app.globalData.userInfo = res.userInfo;
            }
          })
          // 获取地理位置
          if (res.authSetting['scope.userLocation'] != undefined && res.authSetting['scope.userLocation'] != true) {
            
            wx.showModal({
              title: '提示',
              content: '需要获取您的地理位置，请确认授权，否则功能无法使用',
              success: function (res) {
                if (res.cancel) {
                  that.setData({
                    isshowCIty: false
                  })
                  wx.openSetting({
                    success: function (dataAu) {
                      if (dataAu.authSetting["scope.userLocation"] == true) {
                        wx.showToast({
                          title: '授权成功',
                          icon: 'success',
                          duration: 1000
                        })
                        //再次授权，调用getLocationt的API
                        that.getLocation();
                      } else {
                        wx.showToast({
                          title: '授权失败',
                          icon: 'none',
                          duration: 1000
                        })
                        that.getLocation();
                      }
                    }
                  })
                 
                } else if (res.confirm) {
                  // console.log(res.confirm)
                  wx.openSetting({
                    success: function (dataAu) {
                      // console.log(dataAu)
                      if (dataAu.authSetting["scope.userLocation"] == true) {
                        wx.showToast({
                          title: '授权成功',
                          icon: 'success',
                          duration: 1000
                        })
                        //再次授权，调用getLocationt的API
                        that.getLocation();
                      } else {
                        wx.showToast({
                          title: '授权失败',
                          icon: 'none',
                          duration: 1000
                        })
                        that.getLocation();
                      }
                    }
                  })
                }
              }
            })
          } else if (res.authSetting['scope.userLocation'] == undefined) {//初始化进入
          console.log("没有授权地理位置");
            that.getLocation();
            
          }
          else { //授权后默认加载
            that.getLocation();
          }
          
        } else {
          // that.getLocation()
          // console.log(1)
          that.setData({
            isHide: false
          })
        }
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
    this.onLoad({ id: this.data.order_id, d: true })
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