import pandas as pd
import matplotlib.pyplot as plt
import numpy as np
import seaborn as sns
from sklearn.ensemble import RandomForestRegressor
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error, r2_score
from sqlalchemy import create_engine
import joblib

# ================= DATABASE CONNECTION =================
engine = create_engine('mysql+mysqlconnector://root:@localhost/dopas')

# ================= LOAD DATA (Week 1–45) =================
query = """
SELECT
    d.mukimId,
    d.Week,
    c.Temperature,
    c.Rainfall,
    c.Humidity,
    d.dengueCases
FROM denguerecords d
JOIN climatedata c
    ON d.mukimId = c.mukimId
   AND d.Week = c.Week
WHERE d.Week BETWEEN 1 AND 45
ORDER BY d.mukimId, d.Week
"""
data = pd.read_sql(query, engine)

# ================= DATA CLEANING =================
data.dropna(inplace=True)
data['Week'] = data['Week'].astype(int)
data['dengueCases'] = data['dengueCases'].astype(int)

# ================= LAG FEATURE =================
data['dengueCases_lag1'] = data.groupby('mukimId')['dengueCases'].shift(1)
data = data.dropna()

# ================== CONFIG: CHOOSE TEST WEEKS ==================
TEST_WEEK_START = 20  # 👈 CHANGE THIS
TEST_WEEK_END   = 30   # 👈 CHANGE THIS

# ================= TRAIN / TEST SPLIT =================
test_data = data[
    (data['Week'] >= TEST_WEEK_START) &
    (data['Week'] <= TEST_WEEK_END)
]

train_data = data[
    (data['Week'] < TEST_WEEK_START) |
    (data['Week'] > TEST_WEEK_END)
]

print(f"Training weeks: < {TEST_WEEK_START} and > {TEST_WEEK_END}")
print(f"Testing weeks: {TEST_WEEK_START}–{TEST_WEEK_END}")
print("Training shape:", train_data.shape)
print("Testing shape:", test_data.shape)

# ================= FEATURES & TARGET =================
feature_cols = ['Temperature', 'Rainfall', 'Humidity']

X_train = train_data[feature_cols]
y_train = train_data['dengueCases']

X_test = test_data[feature_cols]
y_test = test_data['dengueCases']

# ================= MODEL TRAINING =================
rf_model = RandomForestRegressor(
    n_estimators=300,
    max_depth=2,
    min_samples_split=2,
    min_samples_leaf=2,
    random_state=5
)

rf_model.fit(X_train, y_train)

# ================= PREDICTION =================
preds = rf_model.predict(X_test)

# ================= EVALUATION =================
mse = mean_squared_error(y_test, preds)
r2 = r2_score(y_test, preds)

print(f"MSE ({TEST_WEEK_START}-{TEST_WEEK_END}):", mse)
print(f"R² ({TEST_WEEK_START}-{TEST_WEEK_END}):", r2)

# ================= VISUALIZATION =================
plt.figure(figsize=(10, 6))
sns.set(style='whitegrid')

# Scatter plot of actual vs predicted
plt.scatter(y_test, preds, color='blue', alpha=0.7, s=50, label='Data points')

# Fit a linear regression model to the data points to get the "touching" line
lr = LinearRegression()
lr.fit(y_test.values.reshape(-1, 1), preds)
slope = lr.coef_[0]
intercept = lr.intercept_

# Generate regression line values
x_vals = np.linspace(min(y_test), max(y_test), 100)
y_vals = slope * x_vals + intercept

# Plot the best-fit regression line in red
plt.plot(
    x_vals, y_vals, color='red', linewidth=2,
    label=f'Best fit: y={slope:.2f}x + {intercept:.2f}'
)

plt.xlabel("Actual Dengue Cases")
plt.ylabel("Predicted Dengue Cases")
plt.title(
    f"Actual vs Predicted Dengue Cases "
    f"(Test Week {TEST_WEEK_START}–{TEST_WEEK_END})"
)
plt.legend()
plt.grid(True)
plt.show()

# ================= SAVE MODEL (OPTIONAL) =================
joblib.dump(rf_model, r"C:\xampp\htdocs\PSM\model\dengue_model.pkl")

# ================= OUTPUT TABLE =================
output = test_data.copy()
output['PredictedDengueCases'] = preds.round(0).astype(int)

print("\n=== Prediction Output ===")
print(
    output[['mukimId', 'Week', 'dengueCases', 'PredictedDengueCases']]
    .sort_values(['mukimId', 'Week'])
    .to_string(index=False)
)
