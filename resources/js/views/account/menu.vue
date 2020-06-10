<template>
  <div>
    <el-alert type="success" :closable="false" style="margin-bottom: 10px">
      <div slot="title">
        <a href="https://developers.weixin.qq.com/doc/offiaccount/Custom_Menus/Creating_Custom-Defined_Menu.html" target="_blank">
          <el-button type="text">微信自定义菜单文档链接</el-button>
        </a>
        |
        <a href="https://mp.weixin.qq.com/debug/cgi-bin/sandboxinfo?action=showinfo&t=sandbox/index" target="_blank">
          <el-button type="text">微信公众测试号链接</el-button>
        </a>
      </div>
    </el-alert>

    <we-chat-menu-editor ref="weChatMenuEditor" v-model="menu" />

    <div class="operation">
      <el-button type="primary" size="small" @click="handleSave">保存</el-button>
      <el-button type="primary" size="small" @click="handleReset">重置</el-button>
    </div>
  </div>
</template>

<script>
import WeChatMenuEditor from '../components/WeChatMenuEditor'
import { store } from '../../api/menu'

export default {
  name: 'Menu',
  components: { WeChatMenuEditor },
  inject: ['account'],
  data() {
    return {
      menu: {}
    }
  },
  methods: {
    async handleSave() {
      if (!this.$refs.weChatMenuEditor.validate()) return this.$message.error('请检查参数')

      const loading = this.$loading({ text: '新增中,请稍等...' })

      try {
        await store(this.account.id, this.menu)

        this.$message.success('请求成功')
      } catch (e) {
        console.error(e)
        this.$message.error('操作失败')
      }

      loading.close()
    },
    handleReset() {
      this.menu = {}
      this.$refs.weChatMenuEditor.toggleSelectedArrPath()
    }
  }
}
</script>

<style lang="scss" scoped>
  img {
    max-height: 100px;
    max-width: 100px;
  }
  .wrap {
    display: flex;
    .print {
      width: 500px;
      margin-left: 15px;
      height: 100%;
      overflow: auto;
    }
  }
  .operation {
    margin-top: 10px;
    display: flex;
    justify-content: center;
    align-content: center;
  }
</style>
