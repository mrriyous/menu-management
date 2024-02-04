export function storageSetData(identifider: string, value: any) {
    localStorage.setItem(identifider, value);
}

export function storageGetData(identifider: string) {
    localStorage.getItem(identifider);
}

export function storageRemoveData(identifider: string) {
    localStorage.removeItem(identifider);
}
