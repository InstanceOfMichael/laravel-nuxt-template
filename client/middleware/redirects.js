export default async function ({ route, redirect }) {
  if (route.path.startsWith('/u/')) {
    redirect(route.fullPath.replace('/u/', '/users/'))
  }
  if (route.path.startsWith('/user/')) {
    redirect(route.fullPath.replace('/user/', '/users/'))
  }
  if (route.path.startsWith('/q/')) {
    redirect(route.fullPath.replace('/q/', '/questions/'))
  }
  if (route.path.startsWith('/a/')) {
    redirect(route.fullPath.replace('/a/', '/answers/'))
  }
}
