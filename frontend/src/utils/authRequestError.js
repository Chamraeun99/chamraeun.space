/** Maps axios errors from login/register into a user-visible string. */
export function formatAuthRequestMessage(error, fallbackMessage) {
  const data = error?.response?.data
  const errs = data?.errors
  if (errs && typeof errs === 'object') {
    return Object.values(errs).flat().join(', ')
  }
  if (!error?.response) {
    return (
      'Could not reach the server. On free tiers the API may be waking up — wait about 30 seconds and try again. ' +
      'If this continues, disable VPN/ad blockers or try another network.'
    )
  }
  if (error.response.status === 502) {
    return 'Server is starting up. Please wait a moment and try again.'
  }
  return data?.message || fallbackMessage || 'Something went wrong.'
}
