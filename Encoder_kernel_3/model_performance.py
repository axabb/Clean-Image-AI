import os
import numpy as np
import tensorflow as tf
from tensorflow.keras.models import load_model
from skimage.metrics import peak_signal_noise_ratio
from PIL import Image
import matplotlib.pyplot as plt

# Load the trained model
model = load_model('C:\\xampp\\htdocs\\cleanimageai\\Encoder_kernel_3\\D_model50.h5')

# Function to load and normalize images
def load_and_normalize_image(image_path):
    try:
        image = Image.open(image_path).convert('L')  # Ensure it's in grayscale
        image = image.resize((400, 400), Image.LANCZOS)  # Resize to 400x400 with LANCZOS resampling
        image_array = np.asarray(image).astype(np.float32) / 255.0
        image_array = np.expand_dims(image_array, axis=-1)  # Add channel dimension
        return image_array
    except PermissionError:
        print(f"Permission denied: {image_path}")
        return None
    except FileNotFoundError:
        print(f"File not found: {image_path}")
        return None
    except Exception as e:
        print(f"Error loading image: {image_path}, {e}")
        return None

# Paths to the directories containing noisy and clean images
noisy_image_dir = 'C:\\xampp\\htdocs\\cleanimageai\\dataset\\dataset\\test_noisy'
clean_image_dir = 'C:\\xampp\\htdocs\\cleanimageai\\dataset\\dataset\\test_cleaned'

# Get all image paths in the directories
noisy_image_paths = [os.path.join(noisy_image_dir, fname) for fname in os.listdir(noisy_image_dir) if fname.lower().endswith(('png', 'jpg', 'jpeg'))]
ground_truth_image_paths = [os.path.join(clean_image_dir, fname) for fname in os.listdir(clean_image_dir) if fname.lower().endswith(('png', 'jpg', 'jpeg'))]

# Ensure the lists are sorted to match corresponding noisy and clean images
noisy_image_paths.sort()
ground_truth_image_paths.sort()

# Load and normalize images
noisy_images = np.array([img for img in (load_and_normalize_image(path) for path in noisy_image_paths) if img is not None])
ground_truth_images = np.array([img for img in (load_and_normalize_image(path) for path in ground_truth_image_paths) if img is not None])

# Ensure the images are loaded correctly
if len(noisy_images) != len(ground_truth_images):
    raise ValueError("The number of noisy and clean images do not match!")

# Generate denoised images
denoised_images = model.predict(noisy_images)

# Initialize lists to hold metric values
psnr_values = []
rmse_values = []

# Compute PSNR and RMSE for each image
for gt, denoised in zip(ground_truth_images, denoised_images):
    psnr = peak_signal_noise_ratio(gt, denoised)
    mse = np.mean(np.square(gt - denoised))
    rmse = np.sqrt(mse)
    
    psnr_values.append(psnr)
    rmse_values.append(rmse)

# Compute average metrics
average_psnr = np.mean(psnr_values)
average_rmse = np.mean(rmse_values)

print(f"Average PSNR: {average_psnr}")
print(f"Average RMSE: {average_rmse}")
