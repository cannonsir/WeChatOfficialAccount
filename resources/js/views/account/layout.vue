<template>
  <div class="wrap">
    <div class="account">
      <div class="head">
        <section>
          <div class="avatar-title" @click="handleClickTitle">
            <el-avatar size="large" :src="account.avatar" />
            <div class="title">{{ account.name }}</div>
          </div>
          <el-tag size="small" style="margin-left: 10px">{{ account.type }}</el-tag>
        </section>
        <section>
          <div class="btn" @click="$router.push({ name: 'WeChatOfficialAccount.setting' })">
            <i class="el-icon-setting" />
            <span>公众号设置</span>
          </div>
          <div class="btn" @click="$router.push({ name: 'WeChatOfficialAccounts' })">
            <i class="el-icon-refresh" />
            <span>切换公众号</span>
          </div>
          <div class="btn">
            <el-popover
                    placement="bottom"
                    width="150"
                    trigger="hover"
            >
              <div slot="reference">
                <i class="el-icon-news" />
                <span>二维码</span>
              </div>
              <div class="qr_code" style="width: 126px;height: 126px;background: #eae9e9">
                <el-avatar v-if="account.qr_code" style="height:100%;width: 100%;background: #eae9e9" shape="square" size="large" :src="account.qr_code" />
                <div v-else style="height: 100%;width: 100%;display: flex;align-items: center;justify-content: center">
                  <el-button type="text" @click="$router.push({ name: 'WeChatOfficialAccount.setting' })">点击设置</el-button>
                </div>
              </div>
            </el-popover>
          </div>

        </section>
      </div>
      <div class="content">
        <div class="menu">
          <el-menu
                  class="el-menu"
                  :default-active="$route.name"
          >
            <el-menu-item v-for="(menu) in menus" :key="menu.routeName" :index="menu.routeName" @click="handleClickMenu(menu)">
              <i :class="menu.icon" />
              <span slot="title">{{ menu.title }}</span>
            </el-menu-item>
          </el-menu>
        </div>
        <router-view class="children-route-view" v-if="account.id" />
      </div>
    </div>
  </div>
</template>

<script>
import { show } from '../../api/account'
import Vue from 'vue'

const observable = Vue.observable({
  account: {}
})

export default {
  name: 'AccountLayout',
  props: {
    id: {
      type: String,
      required: true
    }
  },
  provide() {
    return { account: observable.account }
  },
  data: () => observable,
  computed: {
    menus() {
      return [
        {
          title: '概览',
          routeName: 'WeChatOfficialAccount.index',
          icon: 'el-icon-s-operation'
        },
        {
          title: '自定义菜单',
          routeName: 'WeChatOfficialAccount.menu',
          icon: 'el-icon-chat-dot-round'
        },
        {
          title: '用户管理',
          routeName: 'WeChatOfficialAccount.user',
          icon: 'el-icon-user'
        },
        {
          title: '素材管理',
          routeName: 'WeChatOfficialAccount.material',
          icon: 'el-icon-brush'
        },
        {
          title: '公众号设置',
          routeName: 'WeChatOfficialAccount.setting',
          icon: 'el-icon-setting'
        }
      ]
    }
  },
  async beforeRouteEnter(to, from, next) {
    try {
      const { data } = await show(to.params.id)

      observable.account = data

      next()
    } catch (e) {
      next(false)
    }
  },
  async beforeRouteUpdate(to, from, next) {
    try {
      await this.fetchAccount(to.params.id)
      next()
    } catch (e) {
      next(false)
    }
  },
  methods: {
    async fetchAccount(id = null) {
      const { data } = await show(id || this.$route.params.id)
      observable.account = data
    },
    handleClickMenu(menu) {
      this.$route.name !== menu.routeName && this.$router.push({ name: menu.routeName })
    },
    handleClickTitle() {
      this.$route.name !== 'WeChatOfficialAccount.index' && this.$router.push({ name: 'WeChatOfficialAccount.index' })
    }
  }
}
</script>

<style lang="scss" scoped>
.wrap {
  margin: 15px auto 0 auto;
  width: calc(100% - 30px);
  height: calc(100vh - 55px - 100px - 15px);
  background: white;
  .account {
    width: 100%;
    height: 100%;
    overflow: hidden;
    border: 1px solid #e3e3e3;
    box-shadow: 0 0 4px 2px rgba(0,0,0,.09);
    .head {
      height: 50px;
      padding: 0 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #e3e3e3;
      section {
        display: flex;
        align-items: center;
      }
      .btn {
        cursor: pointer;
        display: flex;
        align-items: center;
        font-size: .9rem;
        &:hover {
          color: #13a1cc;
        }
        &:not(:first-of-type) {
          margin-left: 10px;
        }
      }
      .avatar-title {
        display: flex;
        align-items: center;
        cursor: pointer;
        &:hover {
          color: #13a1cc;
        }
      }
      .title {
        margin-left: 10px;
        font-size: 1.2rem;
        font-weight: bold;
      }
    }
    .content {
      display: flex;
      width: 100%;
      height: calc(100% - 50px);
      overflow: hidden;
      .menu {
        width: 200px;
        .el-menu {
          height: 100%;
        }
      }
      .children-route-view {
        height: 100%;
        width: 100%;
        overflow: auto;
        padding: 10px;
        &::-webkit-scrollbar {
          width: 3px;     /*高宽分别对应横竖滚动条的尺寸*/
          height: 1px;
        }
        &::-webkit-scrollbar-thumb {
          border-radius: 10px;
          -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
          background: #c2d7f0;
        }
        &::-webkit-scrollbar-track {
          border-radius: 10px;
        }
      }
    }
  }
}
</style>
