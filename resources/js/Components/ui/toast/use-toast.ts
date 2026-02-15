import { computed, ref } from 'vue'

const TOAST_LIMIT = 1
const TOAST_REMOVE_DELAY = 1000000

type ToasterToast = {
    id: string
    dismiss: (open?: boolean) => void
    update: (props: ToasterToast) => void
    title?: string
    description?: string
    action?: any
    variant?: 'default' | 'destructive'
}

let count = 0

function genId() {
    count = (count + 1) % Number.MAX_SAFE_INTEGER
    return count.toString()
}

type ActionType = {
    addToast: (toast: ToasterToast) => void
    updateToast: (toast: ToasterToast) => void
    dismissToast: (toastId?: string) => void
    removeToast: (toastId?: string) => void
}

let memoryState: { toasts: ToasterToast[] } = { toasts: [] }

const listeners: Array<(state: typeof memoryState) => void> = []

function dispatch(action: any) {
    memoryState = reducer(memoryState, action)
    listeners.forEach((listener) => {
        listener(memoryState)
    })
}

function reducer(state: typeof memoryState, action: any) {
    switch (action.type) {
        case 'ADD_TOAST':
            return {
                ...state,
                toasts: [action.toast, ...state.toasts].slice(0, TOAST_LIMIT),
            }

        case 'UPDATE_TOAST':
            return {
                ...state,
                toasts: state.toasts.map((t) =>
                    t.id === action.toast.id ? { ...t, ...action.toast } : t
                ),
            }

        case 'DISMISS_TOAST': {
            const { toastId } = action

            // ! Side effects ! - This could be extracted into a dismissToast() action,
            // but I'll keep it here for simplicity
            if (toastId) {
                addToRemoveQueue(toastId)
            } else {
                state.toasts.forEach((toast) => {
                    addToRemoveQueue(toast.id)
                })
            }

            return {
                ...state,
                toasts: state.toasts.map((t) =>
                    t.id === toastId || toastId === undefined
                        ? {
                            ...t,
                            open: false,
                        }
                        : t
                ),
            }
        }
        case 'REMOVE_TOAST':
            if (action.toastId === undefined) {
                return {
                    ...state,
                    toasts: [],
                }
            }
            return {
                ...state,
                toasts: state.toasts.filter((t) => t.id !== action.toastId),
            }
    }
}

const toastTimeouts = new Map<string, ReturnType<typeof setTimeout>>()

function addToRemoveQueue(toastId: string) {
    if (toastTimeouts.has(toastId)) {
        return
    }

    const timeout = setTimeout(() => {
        toastTimeouts.delete(toastId)
        dispatch({
            type: 'REMOVE_TOAST',
            toastId,
        })
    }, TOAST_REMOVE_DELAY)

    toastTimeouts.set(toastId, timeout)
}

function useToast() {
    const state = ref(memoryState)

    if (listeners.indexOf((newState) => (state.value = newState)) === -1) {
        listeners.push((newState) => {
            state.value = newState
        })
    }

    return {
        toasts: computed(() => state.value.toasts),
        toast,
        dismiss: (toastId?: string) => dispatch({ type: 'DISMISS_TOAST', toastId }),
    }
}

function toast(props: Omit<ToasterToast, 'id' | 'dismiss' | 'update'>) {
    const id = genId()

    const update = (props: ToasterToast) =>
        dispatch({
            type: 'UPDATE_TOAST',
            toast: { ...props, id },
        })
    const dismiss = () => dispatch({ type: 'DISMISS_TOAST', toastId: id })

    dispatch({
        type: 'ADD_TOAST',
        toast: {
            ...props,
            id,
            open: true,
            onOpenChange: (open: boolean) => {
                if (!open) dismiss()
            },
        },
    })

    return {
        id,
        dismiss,
        update,
    }
}

export { useToast, toast }
