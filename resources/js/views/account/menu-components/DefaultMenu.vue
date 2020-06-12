<template>
    <div>
      <we-chat-menu-editor v-loading="loading" ref="weChatMenuEditor" v-model="menu" />

      <div class="operation" v-if="changed">
        <el-button type="primary" size="small" @click="handleSave">保存</el-button>
        <el-button type="primary" size="small" @click="fetchCurrentMenu">重置</el-button>
      </div>
    </div>
</template>

<script>
import WeChatMenuEditor from "../../components/WeChatMenuEditor";
import { store, current } from "../../../api/menu";
import _ from 'lodash'

export default {
  name: "DefaultMenu",
  components: { WeChatMenuEditor },
  inject: ['account'],
  data() {
    return {
      loading: false,
      menu: {},
      origin: {}
    }
  },
  computed: {
    changed() {
      return !_.isEqual(this.menu, this.origin)
    }
  },
  created() {
    this.fetchCurrentMenu()
  },
  methods: {
    async fetchCurrentMenu() {
      try {
        this.loading = true
        this.menu = await current(this.account.id)
        this.origin = _.cloneDeep(this.menu)
      } catch (e) {
        console.error(e)
        this.$message.error('拉取菜单失败')
      } finally {
        this.loading = false
      }
    },
    async handleSave() {
      if (!this.$refs.weChatMenuEditor.validate()) return this.$message.error('请检查参数')

      const loading = this.$loading({ text: '新增中,请稍等...' })

      try {
        await store(this.account.id, this.menu)

        this.$message.success('保存成功')

        await this.fetchCurrentMenu()
      } catch (e) {
        console.error(e)
        this.$message.error('保存失败')
      }

      loading.close()
    }
  }
}
</script>

<style scoped>
.operation {
  padding: 20px;
  display: flex;
  justify-content: center;
  align-content: center;
}
</style>
