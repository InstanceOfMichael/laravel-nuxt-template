<template lang="pug">
  .users-show
    img.rounded(
      width="160"
      height="160"
      v-if="user.photo_url"
      :src="user && user.photo_url"
    )
    br
    table.table.table-sm
      tbody
        tr
          th ID
          td {{ user.id }}
        tr
          th Handle
          td {{ user.handle }}
        tr
          th Name
          td {{ user.name }}
        tr
          th Registered
          td: DateTime(:value="user.created_at")
    pre: tt {{ {user} }}
</template>

<script>
export default {
  async asyncData ({ api, route, deslug }) {
    return {
      user: (await api.get(`/users/${deslug(route.params.handle)}`)).data,
    }
  },
}
</script>
