/**
 * Face ID Authentication 
 * Handles the camera access and face detection for authentication
 */

class FaceIdHandler {
    constructor(apiEndpoint) {
        this.apiEndpoint = apiEndpoint;
        this.video = null;
        this.canvas = null;
        this.stream = null;
        this.faceVerified = false;
    }
    
    init(videoElementId, canvasElementId) {
        this.video = document.getElementById(videoElementId);
        this.canvas = document.getElementById(canvasElementId);
        
        if (!this.video || !this.canvas) {
            console.error('Video or canvas element not found');
            return false;
        }
        
        return true;
    }
    
    async startCamera() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ video: true });
            this.video.srcObject = this.stream;
            return true;
        } catch (err) {
            console.error('Error starting camera:', err);
            return false;
        }
    }
    
    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
    }
    
    captureImage() {
        if (!this.video || !this.canvas) return null;
        
        const context = this.canvas.getContext('2d');
        context.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
        return this.canvas.toDataURL('image/jpeg');
    }
    
    async verifyFace() {
        const imageData = this.captureImage();
        if (!imageData) return { success: false, message: 'Failed to capture image' };
        
        try {
            const response = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ image: imageData }),
            });
            
            const result = await response.json();
            this.faceVerified = result.success;
            return result;
        } catch (error) {
            console.error('Error verifying face:', error);
            return { 
                success: false, 
                message: 'Error communicating with the server: ' + error.message 
            };
        }
    }
    
    isFaceVerified() {
        return this.faceVerified;
    }
    
    resetVerification() {
        this.faceVerified = false;
    }
}

// Export the class
window.FaceIdHandler = FaceIdHandler;
