<template>
  <div>
    <el-divider content-position="left">参数设置</el-divider>

    <account-sheet :value="account" @submit="handleUpdateAccount" />

    <el-divider content-position="left">其他操作</el-divider>

    <div style="margin-bottom: 20px">
      <el-button type="danger" @click="handleDelete">删除此公众号</el-button>
      <el-button @click="handleCleanApiRecent">清理微信接口调用次数</el-button>
      <el-button @click="handleFetchWxIp">获取微信服务器IP</el-button>
    </div>
  </div>
</template>

<script>
import AccountSheet from '../components/AccountSheet'
import { update, destroy, cleanApiInvokeRecord, fetchWxIp } from '../../api/account'

export default {
  name: 'Setting',
  components: { AccountSheet },
  inject: ['account'],
  methods: {
    async handleUpdateAccount(data) {
      try {
        await update(this.account.id, data)
        this.$parent.fetchAccount()
        this.$message.success('更新成功')
      } catch (e) {
        console.error(e)
        this.$message.error('更新失败,请稍后再试')
      }
    },
    handleDelete() {
      this.$alert('确认要删除该公众号吗?', '警告', {
        confirmButtonText: '确定',
        callback: async action => {
          if (action === 'cancel') return

          try {
            await destroy(this.account.id)
            this.$router.push({ name: 'WeChatOfficialAccounts' })
            this.$message.success('删除成功!')
          } catch (e) {
            console.error(e)
            this.$message.error('删除失败')
          }
        }
      })
    },
    async handleCleanApiRecent() {
      const loading = this.$loading({ text: '清理中...' })

      try {
        await cleanApiInvokeRecord(this.account.id)
        this.$message.success('清理成功')
      } catch (e) {
        console.error(e)
        this.$message.error('清理失败')
      } finally {
        loading.close()
      }
    },
    async handleFetchWxIp() {
      const loading = this.$loading({ text: '获取中...' })

      try {
        const ip = await fetchWxIp(this.account.id)

        const h = this.$createElement;

        const ips = (ip.ip_list || []).map(val => h('p', val))

        this.$msgbox({
          title: '微信服务器IP地址列表',
          message: h('div', {
            style: {
              maxHeight: '500px',
              overflow: 'auto'
            }
          }, ips),
          confirmButtonText: '确定',
        })
      } catch (e) {
        console.error(e)
      } finally {
        loading.close()
      }
    }
  }
}
</script>

<style scoped>

</style>
