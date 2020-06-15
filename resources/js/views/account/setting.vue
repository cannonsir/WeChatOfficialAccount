<template>
  <div>
    <el-divider content-position="left">参数设置</el-divider>

    <account-sheet :value="account" @submit="handleUpdateAccount" />

    <el-divider content-position="left">其他操作</el-divider>

    <el-button type="danger" @click="handleDelete" style="margin-bottom: 20px">删除</el-button>
  </div>
</template>

<script>
import AccountSheet from '../components/AccountSheet'
import { update, destroy } from '../../api/account'

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
    }
  }
}
</script>

<style scoped>

</style>
