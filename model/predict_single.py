import sys
import joblib
import pandas as pd
from sqlalchemy import create_engine

if len(sys.argv) != 4:
    print(0)
    sys.exit()

mukimId = int(sys.argv[1])
week    = int(sys.argv[2])
year    = int(sys.argv[3])

# LOAD MODEL 
model = joblib.load(
    r"C:\xampp\htdocs\PSM\model\dengue_model.pkl"
)

engine = create_engine("mysql+mysqlconnector://root:@localhost/dopas")

query = f"""
SELECT Temperature, Rainfall, Humidity
FROM climatedata
WHERE mukimId={mukimId}
  AND Week={week}
  AND Year={year}
LIMIT 1
"""

df = pd.read_sql(query, engine)

if df.empty:
    print(0)
    sys.exit()

df = df[['Temperature', 'Rainfall', 'Humidity']]

prediction = int(round(model.predict(df)[0]))
print(prediction)
