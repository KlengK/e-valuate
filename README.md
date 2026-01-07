# e-valuate: Data-Driven Real Estate Valuation Model

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Python](https://img.shields.io/badge/Python-3.x-yellow.svg)
![Algorithm](https://img.shields.io/badge/Algorithm-Multiple_Linear_Regression-orange)
![Status](https://img.shields.io/badge/Status-Graduate_Thesis-success)

> **Academic Note:** This repository contains the source code, datasets, and predictive models for the graduate thesis titled *"Data-Driven Valuation: A Price Prediction Model for Residential and Condominium Properties."*

## 📖 Project Abstract

**e-valuate** is a predictive analytics tool designed to estimate the market value of residential and condominium properties. By leveraging historical real estate data and applying **Multiple Linear Regression (MLR)**, this project aims to provide a data-driven alternative to traditional, subjective appraisal methods.

The system analyzes key independent variables (e.g., floor area, location, number of bedrooms, building age) to generate a predicted market price with statistical significance.

## 🎯 Key Objectives

* **Predictive Accuracy:** Develop a regression model that minimizes the error between predicted and actual property prices.
* **Market Insight:** Identify which attributes (variables) have the highest correlation with property value.
* **Decision Support:** Provide a tool for stakeholders (buyers, sellers, agents) to assess fair market value based on quantitative data.

## ⚙️ Methodology & Tech Stack

This project follows the **CRISP-DM** (Cross-Industry Standard Process for Data Mining) methodology:

* **Language:** Python
* **Data Manipulation:** Pandas, NumPy
* **Machine Learning:** Scikit-learn (LinearRegression model)
* **Visualization:** Matplotlib, Seaborn
* **Environment:** Jupyter Notebook / Google Colab

### The Algorithm: Multiple Linear Regression
The core model attempts to fit a linear equation to observed data:
$$Y = \beta_0 + \beta_1X_1 + \beta_2X_2 + ... + \beta_nX_n + \epsilon$$

Where:
* $Y$ = Predicted Price
* $X_1, X_2...$ = Property Features (Size, Location, Amenities)

## 📊 Model Performance

*(Update these values with your actual thesis results)*

| Metric | Score | Description |
| :--- | :--- | :--- |
| **R-squared ($R^2$)** | **0.XX** | Explains XX% of the variance in property prices. |
| **RMSE** | **₱XXX,XXX** | Root Mean Square Error (average deviation). |
| **MAE** | **₱XXX,XXX** | Mean Absolute Error. |

## 📂 Repository Structure
