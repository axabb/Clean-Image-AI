from flask import Flask, request, render_template, send_file
import os
import cv2
import numpy as np
from PIL import Image
import pytesseract as tess
tess.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'
import hashlib
import tensorflow as tf

app = Flask(__name__)

# Load the trained model
model = tf.keras.models.load_model(r'C:\xampp\htdocs\cleanimageai\Encoder_kernel_3\D_model50.h5')

# Function to preprocess the image for the model
def preprocess_image(image):
    original_size = image.shape  # Store original size
    image = cv2.resize(image, (400, 400))  # Resize image to match model input
    image = image.astype('float32') / 255.0  # Normalize the image
    image = np.expand_dims(image, axis=-1)  # Add channel dimension
    image = np.expand_dims(image, axis=0)  # Add batch dimension
    return image, original_size

# Function to postprocess the model output
def postprocess_image(denoised_img, original_size):
    denoised_img = np.squeeze(denoised_img, axis=0)  # Remove batch dimension
    denoised_img = np.squeeze(denoised_img, axis=-1)  # Remove channel dimension
    denoised_img = (denoised_img * 255).astype('uint8')  # Denormalize the image
    denoised_img = cv2.resize(denoised_img, (original_size[1], original_size[0]))  # Resize back to original size
    return denoised_img

# Function to denoise an image using the trained model
def denoise_image(noisy_img):
    preprocessed_img, original_size = preprocess_image(noisy_img)
    denoised_img = model.predict(preprocessed_img)
    denoised_img = postprocess_image(denoised_img, original_size)
    return denoised_img

# Function to extract text from an image using pytesseract
def text_extract(image_path):
    img = Image.open(image_path)
    text = tess.image_to_string(img)
    return text

@app.route('/')
def index():
    return render_template('home.php')

@app.route('/denoise.php')
def denoise():
    return render_template('denoise.php')

@app.route('/extract.php')
def extract():
    return render_template('extract.php')

@app.route('/feedback.php')
def feedback():
    return render_template('feedback.php')

@app.route('/profile.php')
def profile():
    return render_template('profile.php')

@app.route('/login.php')
def login():
    return render_template('login.php')

@app.route('/signup.php')
def signup():
    return render_template('signup.php')

@app.route('/denoise_img', methods=['POST'])
def denoise_img():
    if 'file' not in request.files:
        return 'No file part'

    file = request.files['file']

    if file.filename == '':
        return 'No selected file'

    if file:
        # Save the uploaded file
        file_path = os.path.join('uploads', file.filename)
        file.save(file_path)

        # Read the uploaded image
        noisy_img = cv2.imread(file_path, cv2.IMREAD_GRAYSCALE)

        # Apply the trained model for denoising
        denoised_img = denoise_image(noisy_img)

        # Generate a unique filename using MD5 hash
        file_hash = hashlib.md5(file.filename.encode()).hexdigest()
        result_path_denoise = os.path.join('static', f'denoised_result_{file_hash}.jpg')

        # Save the denoised image
        cv2.imwrite(result_path_denoise, denoised_img)

        return render_template('result.html', result_path=result_path_denoise)

@app.route('/text_extract', methods=['POST'])
def text_extract_route():
    if 'file' not in request.files:
        return 'No file part'

    file = request.files['file']

    if file.filename == '':
        return 'No selected file'

    if file:
        # Save the uploaded file
        file_path = os.path.join('uploads', file.filename)
        file.save(file_path)

        # Extract text from the image
        extracted_text = text_extract(file_path)
        
        # Generate a unique filename for the output TXT file
        file_hash = hashlib.md5(file.filename.encode()).hexdigest()
        output_txt_path = os.path.join('static', f'extracted_text_{file_hash}.txt')

        # Save extracted text to the output TXT file
        with open(output_txt_path, 'w') as txt_file:
            txt_file.write(extracted_text)

        return send_file(output_txt_path, as_attachment=True)

if __name__ == '__main__':
    app.run(debug=True)

