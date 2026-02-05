import imageCompression from 'browser-image-compression'

window.compressImage = async function (file) {
    const options = {
        maxSizeMB: 2,
        maxWidthOrHeight: 1600,
        useWebWorker: true,
    }

    return await imageCompression(file, options)
}