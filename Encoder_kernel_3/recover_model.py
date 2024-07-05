# Importing Necessary Libraries
import os
import numpy as np
import matplotlib.pyplot as plt
import cv2
from sklearn.model_selection import train_test_split
import tensorflow as tf
from tensorflow.keras.layers import Input, Conv2D, MaxPool2D, UpSampling2D, BatchNormalization
from tensorflow.keras.models import Sequential
from tensorflow.keras.callbacks import ModelCheckpoint, EarlyStopping

# Function to load images and resize
def load_images_and_resize(folder):
    images = []
    for filename in os.listdir(folder):
        img_path = os.path.join(folder, filename)  # Join folder path and filename
        img = cv2.imread(img_path, cv2.IMREAD_GRAYSCALE)  # Load the image in grayscale
        if img is not None:
            img = cv2.resize(img, (400, 400))  # Resize the image to 400x400 pixels
            images.append(img)  # Append the resized image to the list
        else:
            print(f"Failed to load image from {img_path}")  # Print an error message if the image failed to load
    return images

# Define paths of image directories (update these paths according to your local environment)
path_train_noisy = 'C:\\xampp\\htdocs\\cleanimageai\\dataset\\dataset\\train_noisy\\'
path_train_clean = 'C:\\xampp\\htdocs\\cleanimageai\\dataset\\dataset\\train_cleaned\\'
path_test_noisy = 'C:\\xampp\\htdocs\\cleanimageai\\dataset\\dataset\\test_noisy\\'

# Load each directory and resize their images
x_train = load_images_and_resize(path_train_noisy)
y_train = load_images_and_resize(path_train_clean)
x_test = load_images_and_resize(path_test_noisy)

# Convert lists of images to numpy arrays and reshape them to 400x400
x_train = np.array(x_train).reshape(-1, 400, 400, 1)
y_train = np.array(y_train).reshape(-1, 400, 400, 1)
x_test = np.array(x_test).reshape(-1, 400, 400, 1)

# Splitting Training data
x_tr, x_val, y_tr, y_val = train_test_split(x_train, y_train, test_size=0.3, random_state=42)

# Normalize the data
x_tr = x_tr.astype('float32') / 255.0
y_tr = y_tr.astype('float32') / 255.0
x_val = x_val.astype('float32') / 255.0
y_val = y_val.astype('float32') / 255.0
x_test = x_test.astype('float32') / 255.0

# Define the model architecture
model = Sequential()
model.add(Input(shape=(400, 400, 1)))

# Encoder
model.add(Conv2D(filters=128, kernel_size=(3, 3), activation='relu', padding='same', name='Conv1'))
model.add(BatchNormalization(name='BN1'))
model.add(MaxPool2D((2, 2), padding='same', name='pool1'))

# Decoder
model.add(Conv2D(filters=128, kernel_size=(3, 3), activation='relu', padding='same', name='Conv2'))
model.add(UpSampling2D((2, 2), name='upsample1'))
model.add(Conv2D(filters=1, kernel_size=(3, 3), activation='sigmoid', padding='same', name='Conv3'))


# Print the model summary
model.summary()

# Callbacks
early_stopping = EarlyStopping(monitor='val_loss', patience=10, verbose=1, mode='min', restore_best_weights=True)
checkpoint = ModelCheckpoint('best_model.keras', monitor='val_loss', verbose=1, save_best_only=True, mode='min')

# Load the model if checkpoint exists
checkpoint_path = 'best_model.keras'
initial_epoch = 0
if os.path.exists(checkpoint_path):
    model = tf.keras.models.load_model(checkpoint_path)
    print("Model loaded from checkpoint.")
    initial_epoch = 12  # Manually set to the last epoch completed, for example, change this accordingly
else:
    print("No checkpoint found, starting from scratch.")

# Compile the model (ensure the same loss and optimizer)
model.compile(loss='mean_squared_error', optimizer='adam', metrics=['RootMeanSquaredError'])

# Continue training the model
history = model.fit(x_tr, y_tr, validation_data=(x_val, y_val), batch_size=8, epochs=50, initial_epoch=12, callbacks=[early_stopping, checkpoint])

# Evaluate the model on the test data
test_loss, test_rmse = model.evaluate(x_test, x_test, verbose=1)
print(f"Test Loss: {test_loss}")
print(f"Test RMSE: {test_rmse}")

# Make predictions on the test set
predictions = model.predict(x_test)
print(predictions.shape)

# Display some results
f, ax = plt.subplots(2, 2, figsize=(16, 10))
indices = [0, 1]  # Adjust indices according to your data
ax[0, 0].imshow(x_test[indices[0]].reshape(400, 400), cmap='gray')
ax[0, 1].imshow(predictions[indices[0]].reshape(400, 400), cmap='gray')
ax[1, 0].imshow(x_test[indices[1]].reshape(400, 400), cmap='gray')
ax[1, 1].imshow(predictions[indices[1]].reshape(400, 400), cmap='gray')
plt.show()

# Save the model
model.save('D_model50.h5')
